<?php

namespace alimmvc\core\fields;

class fieldHelpblock extends field
{
	public function __construct($name, $caption, $css_class = "")
	{
		parent::__construct($name, "", "");
		$this->caption = $caption;
		$this->css_class = "form-group " . $css_class;
	}

	//выводит тег элемента с всеми параметрами
	public function getHtml() 
	{
		// $this->css_class .= ($this->isValidationError)?"has-error ":"";

		//если элементы управления не пусты учитываем их
		$style     = (!empty($this->css_style))?"style=\"{$this->css_style}\"":"";
		$class     = (!empty($this->css_class))?"class=\"{$this->css_class}\"":"";

		//формируем тег
		$tag = "<div $class $style>\n";
		$tag .= "<p class='help-block'>{$this->caption}</p>\n";
		$tag .= "</div>\n";

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