<?php

namespace alimmvc\public_html\controllers\editor;

use alimmvc\core\controllers;
use alimmvc\core\registry;
use alimmvc\core\fields;
use alimmvc\public_html\models\authUser;

class authController extends controllers
{
	protected $layout = "editor";

	public function __construct()
	{
		//уберем префикс
		$action = preg_replace("/^(" . registry::app()->router::PREFIX_ACTION . ")/i", '', registry::app()->router->_action);

		// проверка авторизации
		if (!authUser::security_mod())
		{
			if ($action != 'registry')
			{
				registry::app()->router->_action = registry::app()->router::PREFIX_ACTION . 'login';
			}
		} 
		else 
		{
			//добавим пользователя в регистр
			registry::app()->user = $_SESSION["user"];
			if ($action == 'login' || $action == 'registry')
			{
				registry::app()->router->_action = registry::app()->router::PREFIX_ACTION . 'index';
			}

			parent::__construct();
		}
	}

    /**
     * goto editor
     */
	public function actionIndex()
	{
		// убираем контроллер
		$url = preg_replace("|/[^/]*$|i", '', registry::app()->router->_url_controller);
		header("Location: " . $url);
	}

	public final function actionLogin($link = "")
	{
		//меняем шаблон по умолчанию
		$this->layout = "auth";

		if ($link == "registry")
		{
			$fields[] = new fields\fieldHelpblock("help-info-reg", "Регистрация прошла успешно!<br>Пожалуйста, войдите в свой аккаунт", "has-success");
		}

		$fields[] = new fields\fieldTextLogin("login", "Логин", getPost('login'), true);
		$fields[] = new fields\fieldTextPassword("pass", "Пароль", getPost('pass'), true);
		$fields[] = new fields\fieldHelpblock("help-info", "Перейти к <a href='".registry::app()->router->_url_controller."/registry'>регистрации</a>");
		$fields[] = new fields\fieldButton("submit", "Войти", "submit", "btn btn-primary");

		$form = new fields\form($fields);

		if (!empty($_POST) && !$form->check())
		{
			if (authUser::security_mod())
			{
				header("Location: {$_SERVER['HTTP_REFERER']}");
			} 
			else 
			{
				// $form->fields[0]->setStrError("");
				$form->fields[0]->trueValidationError();
				// $form->fields[1]->setStrError("");
				$form->fields[1]->trueValidationError();
				$form->addFieldsFirst(new fields\fieldHelpblock("help-valid-error", "Логин или пароль введены неверно", "has-error"));
			}
		}

		return $this->render('login', [
			'title' => 'Вход',
			'form' => $form->getHtml(),
			]);
	}

	public final function actionLogout()
	{
		authUser::logout();
		header("Location: " . registry::app()->router->_url_controller);
	}

	public final function actionRegistry()
	{
		//меняем шаблон по умолчанию
		$this->layout = "auth";

		$fields['firstname'] = new fields\fieldText("firstname", "Имя", getPost('firstname'), true, "Александр");
		$fields['lastname'] = new fields\fieldText("lastname", "Фамилия", getPost('lastname'), false, "Александров");
		$fields['loginreg'] = new fields\fieldTextLogin("loginreg", "Логин", getPost('loginreg'), true);
		// $fields['login']->trueHelpBlock();
		$fields['passreg'] = new fields\fieldTextPassword("passreg", "Пароль", getPost('passreg'), true);
		$fields['passreg2'] = new fields\fieldTextPassword("passreg2", "Повторите пароль", getPost('passreg2'), true);
		$fields['emailreg'] = new fields\fieldTextEmail("emailreg", "E-mail", getPost('emailreg'), false, "vasua@mail.ru");
		$fields['phone'] = new fields\fieldTextPhone("phone", "Телефон", getPost('phone'), false, "+7 999 999 99 99");
		$fields[] = new fields\fieldHelpblock("help-info", "Перейти к <a href='".registry::app()->router->_url_controller."/login'>входу</a>");
		$fields[] = new fields\fieldButton("registry", "Зарегистрировать", "submit", "btn btn-primary");

		$form = new fields\form($fields);

		if (!empty($_POST) && !$form->check())
		{
			if (getPost('passreg') != getPost('passreg2'))
			{
				$form->fields['passreg']->setStrError("Пароли не совпадают");
				$form->fields['passreg2']->setStrError("Пароли не совпадают");
				$form->fields['passreg']->trueValidationError();
				$form->fields['passreg2']->trueValidationError();
			}
			else if (authUser::registryUser([
				'login' => getPost('loginreg'),
				'pass' => md5(getPost('passreg')),
				'regdate' => date("Y-m-d H:i:s"),
				'firstname' => getPost('firstname'),
				'lastname' => getPost('lastname'),
				'email' => getPost('emailreg'),
				'phone' => getPost('phone'),
				]))
			{
				header("Location: " . registry::app()->router->_url_controller . "/login/registry");
			}
			else 
			{
				$form->fields['loginreg']->setStrError("Логин занят");
				$form->fields['loginreg']->trueValidationError();
			}
			// сущестует

			// header("Location: {$_SERVER['HTTP_REFERER']}");
		}

		return $this->render('registry', [
			'title' => 'Регистрация',
			'form' => $form->getHtml(),
			]);
	}

    protected function init()
    {
        // TODO: Implement init() method.
    }
}