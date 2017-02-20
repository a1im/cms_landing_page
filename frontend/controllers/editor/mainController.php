<?php

namespace alimmvc\frontend\controllers\editor;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;
use alimmvc\frontend\models\tableQuery\site;
use alimmvc\frontend\models\tableQuery\siteCss;
use alimmvc\frontend\models\parsHtmlCssJs;

class mainController extends authController
{
	protected function init()
	{
		registry::app()->siteCrud = new site(registry::app()->db);
	}

	public function actionIndex()
	{
		$sitesRead = registry::app()->siteCrud->reads([
			'id_user' => registry::app()->user['id_user'],
			]);

		// форма создания сайта
		$fields['sitename'] = new fields\fieldText("sitename", "Название сайта", getPost('sitename'), true);
		$fields['sitename']->setPattern("|^[a-z][a-z0-9-_]{2,49}$|");
		$fields['sitename']->trueHelpBlock();
		$fields['sitename']->setStrError("Название должно содержать от 3-50 допустимых символов: a-z, 0-9, -, _");
		$fields[] = new fields\fieldButton("create-site", "Добавить сайт", "submit", "btn btn-primary");

		$formCreateSite = new fields\form($fields);

		if (!empty($_POST) && !$formCreateSite->check())
		{
			if (registry::app()->siteCrud->create([
				'id_user' => registry::app()->user['id_user'],
				'sitename' => getPost('sitename'),
				'title' => "Новый сайт",
				]))
			{
				// узнаем id сайта
				$id_site = registry::app()->siteCrud->readIdSite([
					'id_user' => registry::app()->user['id_user'],
					'sitename' => getPost('sitename'),
					]);
				registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], $id_site);
				// добавляем стандартные стили
				$cssData = parsHtmlCssJs::parsCss($id_site, "style-all");
				if (!empty($cssData))
				{
					foreach ($cssData as $data) 
					{
						registry::app()->siteCssCrud->create($data);
					}
				}
				header("Location: {$_SERVER['HTTP_REFERER']}");
			}
			else 
			{
				$formCreateSite->fields['sitename']->setStrError("Такой сайт уже существует, измените название");
				$formCreateSite->fields['sitename']->trueValidationError();
			}
		}

		return $this->render('index', [
			'title' => 'Вошли',
			'login' => registry::app()->user['login'],
			'sitesRead' => $sitesRead,
			'formCreateSite' => $formCreateSite->getHtml(),
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}

	public function actionEdit($id_site)
	{
		$id_user = registry::app()->user['id_user'];
		$siteRead = registry::app()->siteCrud->read([
			'id_user' => $id_user, 
			'id_site' => $id_site,
			]);

		// если сайта не существует
		if (empty($siteRead))
		{
			header("Location: " . registry::app()->router->_url_controller);
		}

		// форма создания сайта
		$fields['sitetitle'] = new fields\fieldText("sitetitle", "Титул сайта", empty(getPost('sitetitle'))?$siteRead['title']:getPost('sitetitle'), false);
		$fields['sitetitle']->setPattern("|^.{0,100}$|iu");
		$fields['sitetitle']->setStrError("Титул должен содержать 3-100 символов");
		$fields[] = new fields\fieldButton("create-site", "Сохранить", "submit", "btn btn-primary");

		$formEditSite = new fields\form($fields);

		if (!empty($_POST) && !$formEditSite->check())
		{
			registry::app()->siteCrud->update([
				'id_user' => $id_user,
				'id_site' => $id_site,
				'title' => getPost('sitetitle'),
				]);
			$formEditSite->addFieldsFirst(new fields\fieldHelpblock("help-valid-succ", "Данные успешно сохранены!", "has-success"));
		}

		return $this->render('edit', [
			'title' => 'Изменить описание сайта',
			'formEditSite' => $formEditSite->getHtml(),
			]);
	}

	public function actionDelete($id_site)
	{
		registry::app()->siteCrud->delete([
			'id_user' => registry::app()->user['id_user'], 
			'id_site' => $id_site,
			]);
		header("Location: " . registry::app()->router->_url_controller);
	}
}

// Сделать чтобы папки вложенностей контроллеров в Views тоже были 