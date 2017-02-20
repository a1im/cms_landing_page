<?php

namespace alimmvc\core\fields;

class fieldTextPhone extends fieldText
{
	protected function init()
	{
		$this->pattern = "|^[+]{0,1}[0-9- ]{10,}$|i";
		$this->strError = "Введите корректный телефон, минимум 10 символов";
	}
}