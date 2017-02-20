<?php

namespace alimmvc\core\fields;

class fieldText extends field
{
	protected $size = 41;
	protected $maxlength = 255;
	protected $isHelpBlock = false;

	public function __construct($name, $caption, $value = "", $isRequired = false, $placeholder = "", $maxlength = 255, $size = 41)
	{
		parent::__construct($name, $caption, "text", $value);

		$this->maxlength  = intval($maxlength);
		$this->size       = intval($size);
		$this->isRequired = boolval($isRequired);
		$this->placeholder = strval($placeholder);
		// $this->pattern    = "#^.{3,}$#i";
		// $this->strError   = "Поле должно содержать миним 3 буквы";
	}

	//выводит тег элемента с всеми параметрами
	public function getHtml() 
	{
		//если элементы управления не пусты учитываем их
		$style     = (!empty($this->css_style))?"style=\"{$this->css_style}\"":"";
		$class     = (!empty($this->css_class))?"class=\"{$this->css_class}\"":"";
		$size      = (!empty($this->size))?"size=\"{$this->size}\"":"";
		$maxlength = (!empty($this->maxlength))?"maxlength=\"{$this->maxlength}\"":"";
		$placeholder = (!empty($this->placeholder))?"placeholder=\"{$this->placeholder}\"":"";

		//если обязательно заполнять ставим *
		if ($this->isRequired) $this->caption .= " *";

		//формируем тег
		$classError = ($this->isValidationError)?"has-error":"";

		$tag = "<div class=\"form-group $classError\" >\n";
		$tag .= "<label for=\"exampleInput{$this->name}\">{$this->caption}</label>\n";
		$tag .= "<input name=\"{$this->name}\" type=\"{$this->type}\" $class id=\"exampleInput{$this->name}\" value=\"{$this->value}\" $size $maxlength $placeholder >\n";
		if ($this->isHelpBlock || $this->isValidationError) $tag .= "<span class=\"help-block\">{$this->strError}</span>\n";
		$tag .= "</div>\n";

		return $tag;
	}

	public function setSize($val)
	{
		$this->size = intval($val);
	}

	public function setMaxlength($val)
	{
		$this->maxlength = intval($val);
	}
}