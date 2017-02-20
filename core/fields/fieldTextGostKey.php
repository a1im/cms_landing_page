<?php

namespace alimmvc\core\fields;

class fieldTextGostKey extends fieldText
{
	public function __construct($name, $caption, $value = "", $isRequired = false, $placeholder = "", $maxlength = 255, $size = 41)
	{
		parent::__construct($name, $caption, $value, $isRequired, $placeholder, $maxlength, $size);

		// $this->pattern = "|^.{32}$|i";
		// $this->strError = "Введите ключ из 32 символов латиницы и цифр";
	}
}