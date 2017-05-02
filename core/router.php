<?php

namespace alimmvc\core;

Class router
{
	const POSTFIX_CONTROLLER = "Controller";
	const PREFIX_ACTION = "action";

    private $_path;
    private $_args = array();
    // контроллер и действие по умолчанию
    public $_controller = 'main' . self::POSTFIX_CONTROLLER;
    public $_action = self::PREFIX_ACTION . 'index';
    private $_dir_file;
    private $_dir_controller = "";
    private $_url_controller = "";
    private $_url_action = "";

    public function __construct() 
    {
        // $this->_dir_controller = str_replace(DIRECTORY_SEPARATOR, '\\', $dir);
    	$this->setDirController();
    }

    // Проверяем папку с контроллером
    private function setDirController()
    {
    	$path = PATH_DIR_MVC_CONTROLLERS;
        $path .= "\\";
        $path = preg_replace("/[\/\\\]$/sui", '', $path);
        // $path = trim($path, '/\\');
		// debug($path);
        if (!is_dir($path))
        {
            throw new \Exception ('Invalid controller path: `' . $path . '`');
        }
        $this->_path = $path;
    }

    private function getController() 
    {
        // Анализируем путь
        $url = trim($_SERVER['REQUEST_URI'], '/\\ ');
        $routes = explode('/', $url);
        $dir_route =  $this->_path;
        $url_route = SITE_URL_NAME;
        foreach ($routes as $route) 
        {
        	if (empty($route))
        	{
        		continue;
        	}
            
        	$tmp_route = $dir_route . DIRECTORY_SEPARATOR . $route;

            // Есть ли папка с таким путём?
            if (is_dir($tmp_route)) 
            {
                $dir_route .= DIRECTORY_SEPARATOR . $route;
                $url_route .= "/$route";
                array_shift($routes);
                continue;
            }

            // Находим файл
            if (is_file($tmp_route . self::POSTFIX_CONTROLLER . '.php')) 
            {
                $this->_controller = $route . self::POSTFIX_CONTROLLER;
                $url_route .= "/$route";
                array_shift($routes);
                break;
            }
        }

        //если url контроллера не изменился то добавляем стандартный
        if ($url_route == SITE_URL_NAME . "/")
        {
            $url_route .= preg_replace("/(" . self::POSTFIX_CONTROLLER . ")$/i", '', $this->_controller);;
        }
        $this->_url_controller = $url_route;

        // Получаем действие
        $action = array_shift($routes);
        // $args = preg_replace("/.*\?/i", '', $action);
        $action = preg_replace("/\?.*/i", '', $action);
        // debug($args);
        if (!empty($action)) 
        {
            $this->_action = self::PREFIX_ACTION . $action;
            $url_route .= "/$action";
        } else {
            //убираем префикс и добавляем action  к url
            $url_route .= "/" . preg_replace("/^(" . self::PREFIX_ACTION . ")/i", '', $this->_action);
        }

        $this->_dir_file = $dir_route . DIRECTORY_SEPARATOR . $this->_controller . '.php';
        // контроллер с подпапками
        $this->_controller = str_replace($this->_path, '', $this->_dir_file);
        // меняем сепоратор под namespace
        $this->_controller = str_replace('/', '\\', $this->_controller);
        // убираем .php 
        $this->_controller = preg_replace('/\.php$/sui', '', $this->_controller);
        $this->_controller = trim($this->_controller, '/\\');
        //url action
        $this->_url_action = $url_route;
        $this->_args = $routes;
    }

    public function loadController() 
    {
        // Анализируем путь
        $this->getController();

        // debug($this);
        // Файл доступен?
        if (!is_readable($this->_dir_file)) 
        {
//			debug($this->_dir_file);
            header("Location: /err/404.php");
        }

        // Подключаем файл
        // include ($this->_dir_file);

        // Создаём экземпляр контроллера
        // $class = $this->_dir_controller . "\\" . $this->_controller;
        $class = "alimmvc\\" . DIR_MVC . "\\controllers\\" . $this->_controller;
        // debug($class);
        
        $controller = new $class();

        // Действие доступно?
        if (!is_callable(array($controller, $this->_action))) 
        {
//			debug($this->_action);
            header("Location: /err/404.php");
        }

        //Проверяем сколько обязательных параметров принимает action функция
        $classRef  = new \ReflectionClass( $class );
        $methodRef = $classRef->getMethod( $this->_action );
        $countRequiredParamRef  = $methodRef->getNumberOfRequiredParameters();
        $countParamRef  = $methodRef->getNumberOfParameters();

        // проверка на кол-во аргументов принимаемых методом
        if (sizeof($this->_args) < $countRequiredParamRef && sizeof($this->_args) > $countParamRef)
        {
//            die ('Not filled required parameters');
            header("Location: /err/404.php");
        }

        // Выполняем действие
        $controller->{$this->_action}(...$this->_args);
    }

    public function setDefaultController($val)
    {
        $this->_controller = strtolower($val) . self::POSTFIX_CONTROLLER;
    }

    public function setDefaultAction($val)
    {
        if ($this->_action === self::PREFIX_ACTION . 'Index') 
        {
            $this->_action = self::PREFIX_ACTION . strtolower($val);
        }
    }

    public function __get($key)
    {
        return $this->$key;
    }
}