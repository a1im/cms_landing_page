<?php

namespace alimmvc\public_html\controllers;

use alimmvc\core\registry;
use alimmvc\core\controllers;
use alimmvc\core\fields;
use alimmvc\public_html\models\messageForm;

class feedbackController extends controllers
{
	public function actionIndex($sort = "date", $sc = "DESC")
	{
		$fields[] = new fields\fieldText("name", "Имя", getPost('name'), false, "Вася");
		$fields[] = new fields\fieldTextEmail("email", "E-mail", getPost('email'), true, "vasua@mail.ru");
		$fieldFile = new fields\fieldTextFile(PATH_DIR_MVC_ASSETS . DIRSEP . "image/avatar" . DIRSEP, "avatar", "Выберите фото", $_FILES);
		$fields[] = $fieldFile;
		$fields[] = new fields\fieldTextarea("text", "Текст", getPost('text'), true, "Комментарий");
		$fields[] = new fields\fieldButton("preview", "Предварительный просмотр", "button");
		$fields[] = new fields\fieldButton("submit", "Отправить", "submit", "btn btn-primary");

		$form = new fields\form($fields);

		$messageForm = new messageForm();
		$messages = $messageForm->selectMessage($sort, $sc);

		if (!empty($_POST) && !$form->check())
		{
			// if (!$fieldFile->uploadFile()) die("img failed");
			$fieldFile->uploadFile();
			$messageForm->insertMessage($form->getDbValue());
			header("Location: {$_SERVER['HTTP_REFERER']}");
		}
		
		return $this->render('index', [
			'title' => 'Обратная связь',
			'form' => $form->getHtml(),
			'messages' => $messages,
			'sort' => $sort,
			'sc' => ($sc == "DESC")?"ASC":"DESC",
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}

	public function actionValidation()
	{
		// debug(registry::app()->router->_args);
		return $this->render('index', [
			'title' => $title,
			]);
	}
}