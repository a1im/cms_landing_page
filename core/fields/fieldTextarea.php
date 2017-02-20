<?php

namespace alimmvc\core\fields;

class fieldTextarea extends field
{
	protected $rows = 41;
	protected $maxlength = 255;

	public function __construct($name, $caption, $value = "", $isRequired = false, $placeholder = "",$rows = 3)
	{
		parent::__construct($name, $caption, "", $value);

		$this->rows       = intval($rows);
		$this->isRequired = boolval($isRequired);
		$this->placeholder = strval($placeholder);
		$this->pattern    = "#^.{3,}$#i";
		$this->strError   = "Поле должно содержать миним 3 символа";
	}

	//выводит тег элемента с всеми параметрами
	public function getHtml() 
	{
		//если элементы управления не пусты учитываем их
		$style     = (!empty($this->css_style))?"style=\"{$this->css_style}\"":"";
		$class     = (!empty($this->css_class))?"class=\"{$this->css_class}\"":"";
		$rows      = (!empty($this->rows))?"rows=\"{$this->rows}\"":"";
		$placeholder = (!empty($this->placeholder))?"placeholder=\"{$this->placeholder}\"":"";

		//если обязательно заполнять ставим *
		if ($this->isRequired) $this->caption .= " *";

		//формируем тег
		$classError = ($this->isHelpBlock)?"has-error":"";

		$tag = "<div class=\"form-group $classError\" >\n";
		$tag .= "<label for=\"exampleInput{$this->name}\">{$this->caption}</label>\n";
		$tag .= "<textarea name=\"{$this->name}\" $class id=\"exampleInput{$this->name}\" $rows $placeholder >{$this->value}</textarea>\n";
		if ($this->isHelpBlock) $tag .= "<span class=\"help-block\">{$this->strError}</span>\n";
		$tag .= "</div>\n";

		return $tag;
	}

	public function setRows($val)
	{
		$this->rows = intval($val);
	}
}