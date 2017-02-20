<?php

namespace alimmvc\frontend\controllers\editor;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;

class profileController extends authController
{
	public function actionIndex()
	{
		return $this->render('index', [
			'title' => 'Профиль',
			'user' => $_SESSION['user'],
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}
}

// Сделать чтобы папки вложенностей контроллеров в Views тоже были 