<?php

namespace alimmvc\public_html\controllers;

use alimmvc\core\registry;
use alimmvc\core\controllers;
use alimmvc\public_html\models\tableQuery\siteTags;
use alimmvc\public_html\models\tableQuery\siteCss;
use alimmvc\public_html\models\tableQuery\site;
use alimmvc\public_html\models\parsHtmlCssJs;

class mainController extends controllers
{
	protected function init()
	{
		registry::app()->siteCrud = new site(registry::app()->db);
	}

	public function actionIndex()
	{
		return $this->render('index', [
			'title' => 'Главная страница',
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}

	public function actionSite($user, $id_site)
	{
		$this->layout = "empty";
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, $user, $id_site);
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, $user, $id_site);
		$tags = registry::app()->siteTagsCrud->reads();
		$styles = registry::app()->siteCssCrud->reads();
		$dataSite = registry::app()->siteCrud->read([
			'id_user' => $user, 
			'id_site' => $id_site,
			]);

		if (empty($dataSite))
		{
			debug("error");
			return ;
		}

		return $this->render('site', [
			'tags' => $tags,
			'styles' => $styles,
			'id_site' => $id_site,
			'dataSite' => $dataSite,
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}
}