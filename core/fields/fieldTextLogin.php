<?php

namespace alimmvc\core\fields;

class fieldTextLogin extends fieldText
{
	protected function init()
	{
		$this->pattern = "|^[a-z][a-z0-9_]{2,}$|i";
		$this->strError = "Введите корректный логин, минимум 3 символа";
	}
}