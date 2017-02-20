<?php

namespace alimmvc\core\fields;

class fieldButton extends field
{
	protected $action = "";

	public function __construct($name, $value, $type = "submit", $css_class = "btn btn-default", $action = "")
	{
		parent::__construct($name, "", $type, $value);
		$this->css_class = $css_class;
		$this->action = $action;
	}

	//выводит тег элемента с всеми параметрами
	public function getHtml() 
	{
		//если элементы управления не пусты учитываем их
		$style     = (!empty($this->css_style))?"style=\"{$this->css_style}\"":"";
		$class     = (!empty($this->css_class))?"class=\"{$this->css_class}\"":"";
		$action    = (!empty($this->css_class))?"action=\"{$this->action}\"":"";

		//формируем тег
		$tag = "<button $style $class $action type=\"{$this->type}\" name=\"{$this->name}\">{$this->value}</button>\n";

		return $tag;
	}

	public function getDbValue()
	{
		return false;
	}

	//проверка зполнения поля
	public function check()
	{

	}
}