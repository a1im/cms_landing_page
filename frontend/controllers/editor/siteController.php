<?php

namespace alimmvc\frontend\controllers\editor;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;
use alimmvc\frontend\models\tableQuery\siteTags;
use alimmvc\frontend\models\tableQuery\siteCss;
use alimmvc\frontend\models\tableQuery\site;
use alimmvc\frontend\models\parsHtmlCssJs;

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
			'styles' => $styles,
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
			foreach ($tagsData as $data) 
			{
				$data['selectors'] = json_encode(parsHtmlCssJs::parsSelectors($data['selectors']));
				// debug($data['selectors']);
				debug($data);
				registry::app()->siteTagsCrud->create($data);
			}
		}
	}

	// добавить CSS код на сайт
	public function actionAddCss($id_site, $nameTemplate)
	{
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], $id_site);
		$cssData = parsHtmlCssJs::parsCss($id_site, $nameTemplate);

		// сделать добавление в базу через 1 запрос для скорости
		if (!empty($cssData))
		{
			foreach ($cssData as $data) 
			{
				debug($data);
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

	public function actionUpdateTagStyle()
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], getPost('id_site'));

		$tag = registry::app()->siteTagsCrud->read(['id_tag' => getPost('id_tag')]);
		$selectors = json_decode($tag['selectors']);
		$selectors->style = getPost('style');
		$selectors = json_encode($selectors);

		// сделать добавление в базу через 1 запрос для скорости
		registry::app()->siteTagsCrud->updateSelectorsTag([
			'selectors' => $selectors,
			'id_tag' => getPost('id_tag'),
			]);
	}
}