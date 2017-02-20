<?php

namespace alimmvc\core;

final class registry
{
	private static $_instance;
	private $_vars = array();

	private function __construct() {}
	private function __clone() {}
	private function __wakeup() {}

	public static function app()
	{
		if (!isset(self::$_instance)) 
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function set($key, $value)
	{
		if (isset(self::app()->_vars[$key])) 
		{
			throw new \Exception('Повторное объявление переменной `' . $key . '`');
		}
		self::app()->_vars[$key] = $value;
		return true;
	}

	public static function get($key) 
	{
		if (!isset(self::app()->_vars[$key])) 
		{
			throw new \Exception('Переменная `' . $key . '` не существует');
			return null;
		}
		return self::app()->_vars[$key];
	}

	public static function remove($key)
	{
		unset(self::app()->_vars[$key]);
	}

	public function __set($key, $value)
	{
		return self::set($key, $value);
	}

	public function __get($key) 
	{
		return self::get($key);
	}

	public function __unset($key)
	{
		self::remove($key);
	}
}