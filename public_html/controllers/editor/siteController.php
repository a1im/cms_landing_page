<?php

namespace alimmvc\public_html\controllers\editor;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;
use alimmvc\public_html\models\tableQuery\siteTags;
use alimmvc\public_html\models\tableQuery\siteCss;
use alimmvc\public_html\models\tableQuery\site;
use alimmvc\public_html\models\parsHtmlCssJs;
use alimmvc\public_html\models\siteTagsPrint;
use alimmvc\public_html\models\saveSite;

class siteController extends authController
{
	protected $layout = "empty";

	protected function init()
	{
		registry::app()->siteCrud = new site(registry::app()->db);
	}

	// редактор
	public function actionEdit($id_site)
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], $id_site);
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], $id_site);
		$tags = registry::app()->siteTagsCrud->reads();
		$styles = registry::app()->siteCssCrud->reads();
		// объединим стили для для одного тега
		$styles2 = [];
		foreach ($styles as $value) {
			$val = $value['selector'];
			if (preg_match('|\*\[id=\'.*\'\]|ui', $val))
			{
				$val = preg_replace('|\*\[id=\'|ui', '', $val);
				$val = preg_replace('|\'\].*|ui', '', $val);
			}
			if (isset($styles2[$val])) 
			{
				$styles2[$val]['styles'] .= "{$value['selector']}{{$value['style']}}";
			}
			else {

				$styles2[$val]['id_tag'] = $val;
				// $styles2[$value['selector']]['id_tag'] = $value['id_tag'];
				$styles2[$val]['styles'] = "{$value['selector']}{{$value['style']}}";
			}
		}
		
		$dataSite = registry::app()->siteCrud->read([
			'id_user' => registry::app()->user['id_user'], 
			'id_site' => $id_site,
			]);

		if (empty($dataSite))
		{
			debug("error");
			return ;
		}

		return $this->render('index', [
			'title' => 'Вошли',
			'tags' => $tags,
			'styles' => $styles2,
			'id_site' => $id_site,
			'dataSite' => $dataSite,
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}
	
	// добавить HTML код на сайт
	public function actionAddHtml($id_site, $nameTemplate, $parent_tag = 0, $index_tag = 0)
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], $id_site);
		$tagsData = parsHtmlCssJs::parsHtml($id_site, $nameTemplate, $parent_tag, $index_tag);

		// сделать добавление в базу через 1 запрос для скорости
		if (!empty($tagsData))
		{
			$printTags = new siteTagsPrint();
			$tags = [];
			$first_id_tag = current($tagsData)['id_tag'];
			foreach ($tagsData as $data) 
			{
				// debug($data);
				// если тег обычный, то не добавляем его в бд, а добавляем его в контент родителя
				if (empty($data['selectors']['class']) || !preg_match('|alm-|iu', $data['selectors']['class']))
				{
					if (!empty($tags[$data['parent_tag']]))
					{
						$tags[$data['parent_tag']]['content'] .= $printTags->contentPrintParent($tagsData, $data['id_tag'], true);
						// debug($tags[$data['parent_tag']]['content']);
					}
				} else
				{
					$data['selectors'] = json_encode($data['selectors']);
					if ($data['parent_tag'] != $parent_tag && !isset($tags[$data['parent_tag']]))
					{
						// debug("111");
						$data['parent_tag'] = end($tags)['id_tag_new'];
					}
					$tags[$data['id_tag']] = $data;
					$tags[$data['id_tag']]['id_tag_new'] = $first_id_tag++;
					// debug($data);
				}
			}
			// debug($tags);
			// добавим в бд
			foreach ($tags as $key => $data)
			{
				// debug($data);
				if (isset($data['id_tag_new']))
				{
					$tags[$key]['id_tag'] = $data['id_tag_new'];
					unset($tags[$key]['id_tag_new']);
					unset($data['id_tag_new']);
				}
				registry::app()->siteTagsCrud->create($data);
			}

			// debug($tags);
			$printTags->editorPrint($tags, $parent_tag);
		}
	}

	// добавить CSS код на сайт
	public function actionAddCss($id_site, $nameTemplate)
	{
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], $id_site);
		$cssData = parsHtmlCssJs::parsFileCss($id_site, $nameTemplate);

		// сделать добавление в базу через 1 запрос для скорости
		if (!empty($cssData))
		{
			foreach ($cssData as $data) 
			{
				// debug($data);
				registry::app()->siteCssCrud->create($data);
			}
		}
	}

	// обновить HTML код
	public function actionUpdateTagIndex()
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));

		// сделать добавление в базу через 1 запрос для скорости
		registry::app()->siteTagsCrud->updateIndexTag([
			'id_tag' => getPost('id_tag'),
			'index_tag' => getPost('index_tag'),
			'parent_tag' => getPost('parent_tag'),
			]);
		echo getPost('id_site') . " " . getPost('index_tag') . " " . getPost('parent_tag');
	}

	public function actionUpdateTagSelector($selector)
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));

		$tag = registry::app()->siteTagsCrud->read(['id_tag' => getPost('id_tag')]);
		$selectors = json_decode($tag['selectors']);
		$selectors->$selector = getPost('value');
		$selectors = json_encode($selectors);

		// сделать добавление в базу через 1 запрос для скорости
		registry::app()->siteTagsCrud->updateSelectorsTag([
			'selectors' => $selectors,
			'id_tag' => getPost('id_tag'),
			]);
	}

	public function actionUpdateTagContent()
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));

		registry::app()->siteTagsCrud->updateContent([
			'content' => getPost('content'),
			'id_tag' => getPost('id_tag'),
			]);
	}

	public function actionDeleteTag()
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));

		registry::app()->siteTagsCrud->delete([
			'id_tag' => getPost('id_tag'),
			]);
	}

	// изменить/добавить CSS код селектору
	public function actionUpdateCssSelector()
	{
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));
		$cssData = parsHtmlCssJs::parsStyleCss(getPost('id_site'), getPost('id_tag'), getPost('styles'));

		// debug($cssData);
		// сделать добавление в базу через 1 запрос для скорости
		if (!empty($cssData))
		{
			foreach ($cssData as $data)
			{
				// если не получилось добавить обновим css
				if (registry::app()->siteCssCrud->create($data) != 1)
				{
					registry::app()->siteCssCrud->update($data);
				}
			}
		}
	}

	public function actionUploadFile()
	{
		/*******************************************************
		* Only these origins will be allowed to upload images *
		******************************************************/
		$accepted_origins = array(SITE_URL_NAME);

		/*********************************************
		* Change this line to set the upload folder *
		*********************************************/
		$imageFolder = PATH_DIR_MVC_ASSETS . DIRSEP . "image" . DIRSEP . "upload" . DIRSEP;

		reset ($_FILES);
		$temp = current($_FILES);
		if (is_uploaded_file($temp['tmp_name'])){
			if (isset($_SERVER['HTTP_ORIGIN'])) {
				// same-origin requests won't set an origin. If the origin is set, it must be valid.
				if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
					header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
				} else {
					header("HTTP/1.0 403 Origin Denied");
					return;
				}
			}

			/*
			If your script needs to receive cookies, set images_upload_credentials : true in
			the configuration and enable the following two headers.
			*/
			// header('Access-Control-Allow-Credentials: true');
			// header('P3P: CP="There is no P3P policy."');

			// Sanitize input
			if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
				header("HTTP/1.0 500 Invalid file name.");
				return;
			}

			// Verify extension
			if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
				header("HTTP/1.0 500 Invalid extension.");
				return;
			}

			// Accept upload if there was no origin, or if it is an accepted origin
			
			if (preg_match("|^blobid(.)*$|ui", $temp['tmp_name'])) {
				// заменяем файл
				$filename = $temp['tmp_name'];
			} else {
				$filename = $temp['name'];
				//если файл существует добавим префикс
				$prefFile = "";
				$i = 1;
				while(file_exists($imageFolder . $prefFile . $filename))
				{
					$prefFile = $i++ . "_";
				}
				$filename = $prefFile . $filename;
			}

			$filetowrite = $imageFolder . $filename;
			if (move_uploaded_file($temp['tmp_name'], $filetowrite))
			{
				@unlink($files['tmp_name']);
			}

			// Respond to the successful upload with JSON.
			// Use a location key to specify the path to the saved image resource.
			// { location : '/your/uploaded/image/file'}
			// header("HTTP/1.0 500 ff" . $filetowrite);
			// return;
			echo json_encode(array('location' => SITE_URL_ASSETS . "/image/upload/{$filename}"));
		} else {
			// Notify editor that the upload failed
			header("HTTP/1.0 500 Server Error");
		}
	}

	// скачать сайт
	public function actionUploadFullSite($id_site)
	{
		// сохраним, вернет путь к архиву
		$pathFile = saveSite::save($id_site);

		// качаем архив
		if(file_exists($pathFile))
		{
			// отдаём файл на скачивание
			header('Content-type: application/zip');
			// header('Content-Disposition: attachment; filename="site_' . $id_site . '.zip"');
			readfile($pathFile);
			// // удаляем zip файл если он существует
			unlink($pathFile);
		}
	}

	// копирование тега
	public function actionCopyTag()
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));
		$tag = registry::app()->siteTagsCrud->read([
			'id_tag' => getPost('id_tag'),
			]);
		$tag['id_tag'] = registry::app()->siteTagsCrud->readLastIdTag() + 1;
		registry::app()->siteTagsCrud->create($tag);
		$printTags = new siteTagsPrint();
		$printTags->editorPrint([$tag], $tag['parent_tag']);

		$cssData = registry::app()->siteCssCrud->readsTag([
			'id_tag' => getPost('id_tag'),
			]);
		// debug($cssData);
		if (!empty($cssData))
		{
			foreach ($cssData as $data) 
			{
				unset($data['id_style']);
				$data['id_tag'] = $tag['id_tag'];
				$data['selector'] = preg_replace("|\[id='\d+|ui", "[id='" . $tag['id_tag'], $data['selector']);
				// debug($data);
				registry::app()->siteCssCrud->create($data);
			}
		}
	}
}