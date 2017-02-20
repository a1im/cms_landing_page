<?php

namespace alimmvc\core\fields;

class fieldTextNumber extends fieldText
{
	public function __construct($name, $caption, $value = "", $isRequired = false, $placeholder = "", $maxlength = 255, $size = 41)
	{
		parent::__construct($name, $caption, $value, $isRequired, $placeholder, $maxlength, $size);

		$this->type = "number";
		$this->pattern = "|^[0-9]*$|i";
		$this->strError = "Введите число";
	}
}