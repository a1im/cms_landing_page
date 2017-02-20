<?php

namespace alimmvc\core;

abstract class controllers
{
	protected $layout = "main";

	public function __construct()
	{
		//путь до текущего контроллера от папки контроллер
		$pathController = preg_replace("|[\\\\/]|i", DIRSEP, get_class($this));
		$pathController = preg_replace("|^.*(" . registry::app()->dir_controllers . DIRSEP . ")|i", '', $pathController);
		registry::app()->dir_views_controller = registry::app()->dir_views . DIRSEP . $pathController;
		$this->init();
	}

	protected function init() {}

	protected function render($file_action, $data = null)
	{
		if(is_array($data)) 
		{
			// преобразуем элементы массива в переменные
			extract($data);
		}
		
		// include registry::app()->dir_views . DIRSEP . get_class($this) . DIRSEP . $file_action . ".php";		
		require_once(registry::app()->dir_views_layouts . DIRSEP . $this->layout . ".php");
	}
}