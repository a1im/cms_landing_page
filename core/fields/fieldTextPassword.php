<?php

namespace alimmvc\core\fields;

class fieldTextPassword extends fieldText
{
	public function __construct($name, $caption, $value = "", $isRequired = false, $maxlength = 255, $size = 41)
	{
		parent::__construct($name, $caption, $value, $isRequired, "", $maxlength, $size);

		$this->type = "password";
		$this->pattern = "|^[a-z0-9_]{3,}$|i";
		$this->strError = "Введите корректный пароль, минимум 3 символа";
	}
}