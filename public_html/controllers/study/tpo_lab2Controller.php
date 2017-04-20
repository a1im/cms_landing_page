<?php

namespace alimmvc\public_html\controllers\study;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;
use alimmvc\public_html\models\recoverDnk;
use alimmvc\tests\public_html\models\testBlackBox;

class tpo_lab2Controller extends controllers
{
	public $layout = "sii";
	
	public function actionIndex()
	{
		$fields[] = new fields\fieldTextDnk("dnk", "фрагменты ДНК (разделитель '-')", getPost('dnk'), true, "АГЦЦ-ЦГГУ-ГГУАА-УААЦЦ");
		$fields[] = new fields\fieldButton("btnStart", "Отправить", "submit", "btn btn-primary");
		$fields[] = new fields\fieldButton("btnCancel", "Отмена", "button");

		$form = new fields\form($fields);

		$recoverDnk = new recoverDnk();

		$dnk = "";
		if (!empty($_POST) && !$form->check())
		{
			$dnk = $recoverDnk->recover($fields[0]->value);
			if (!$dnk) $dnk = "Ошибка восстановления!";
			//header("Location: {$_SERVER['HTTP_REFERER']}");
		}

		return $this->render('index', [
			'title' => "ДНК...",
			'form' => $form->getHtml(),
			'dnk' => $dnk,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}

	public function actionTestblackbox()
	{
		$testBlackBox = new testBlackBox();

		return $this->render('blackbox', [
			'title' => "Тестирование...",
			'res' => $testBlackBox->startTest(),
			]);
	}
}