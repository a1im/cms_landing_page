<?php

namespace alimmvc\backend\controllers;

use alimmvc\core\registry;
use alimmvc\core\fields;

class adminController extends authController
{
	public function actionIndex()
	{
		return $this->render('index', [
			'title' => 'Вы вошли',
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}
}