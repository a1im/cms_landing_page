<?php

namespace alimmvc\core\fields;

class fieldTextEmail extends fieldText
{
	public function __construct($name, $caption, $value = "", $isRequired = false, $placeholder = "", $maxlength = 255, $size = 41)
	{
		parent::__construct($name, $caption, $value, $isRequired, $placeholder, $maxlength, $size);

		$this->type = "email";
		$this->pattern = "#^[-a-z0-9_\.]+@[-a-z0-9^\.]+\.[a-z]{2,6}$#i";
		$this->strError = "Введите корректный емайл";
	}
}