<?php

namespace alimmvc\backend\controllers;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;
use alimmvc\backend\models\authUser;


class authController extends controllers
{
	protected final function init()
	{
		//уберем префикс
		$action = preg_replace("/^(" . registry::app()->router::PREFIX_ACTION . ")/i", '', registry::app()->router->_action);

		if (!authUser::security_mod())
		{
			if ($action != 'registry')
			{
				registry::app()->router->_action = registry::app()->router::PREFIX_ACTION . 'login';
			}
		} 
		else if ($action == 'login' || $action == 'registry')
		{
			registry::app()->router->_action = registry::app()->router::PREFIX_ACTION . 'index';
		}
	}

	public final function actionLogin()
	{
		//меняем шаблон по умолчанию
		$this->layout = "login";

		$fields[] = new fields\fieldTextLogin("login", "Логин", getPost('login'), true);
		$fields[] = new fields\fieldTextPassword("pass", "Пароль", getPost('pass'), true);
		$fields[] = new fields\fieldHelpblock("reg", "Перейти к <a href='".registry::app()->router->_url_controller."/registry'>регистрации</a>");
		$fields[] = new fields\fieldButton("submit", "Войти", "submit", "btn btn-primary");

		$form = new fields\form($fields);

		if (!empty($_POST) && !$form->check())
		{
			header("Location: {$_SERVER['HTTP_REFERER']}");
		}

		return $this->render('login', [
			'title' => 'Вход',
			'form' => $form->getHtml(),
			]);
	}

	public final function actionLogout()
	{
		authUser::logout();
		header("Location: ".registry::app()->router->_url_controller."");
	}

	public final function actionRegistry()
	{
		//меняем шаблон по умолчанию
		$this->layout = "login";

		$fields[] = new fields\fieldTextLogin("login", "Логин", getPost('login'), true);
		$fields[] = new fields\fieldTextPassword("pass", "Пароль", getPost('pass'), true);
		$fields[] = new fields\fieldTextPassword("pass2", "Повторите пароль", getPost('pass2'), true);
		$fields[] = new fields\fieldTextEmail("email", "E-mail", getPost('email'), false, "vasua@mail.ru");
		$fields[] = new fields\fieldTextLogin("phone", "Телефон", getPost('phone'), false);
		$fields[] = new fields\fieldHelpblock("reg", "Перейти к <a href='".registry::app()->router->_url_controller."/login'>входу</a>");
		$fields[] = new fields\fieldButton("registry", "Зарегистрировать", "submit", "btn btn-primary");

		$form = new fields\form($fields);

		if (!empty($_POST) && !$form->check())
		{
			header("Location: {$_SERVER['HTTP_REFERER']}");
		}

		return $this->render('registry', [
			'title' => 'Регистрация',
			'form' => $form->getHtml(),
			]);
	}
}