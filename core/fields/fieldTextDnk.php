<?php

namespace alimmvc\core\fields;

class fieldTextDnk extends fieldText
{
	public function __construct($name, $caption, $value = "", $isRequired = false, $placeholder = "", $maxlength = 255, $size = 41)
	{
		parent::__construct($name, $caption, $value, $isRequired, $placeholder, $maxlength, $size);

		$this->pattern = "/^[АаГгЦцУу]+(-[АаГгЦцУу]+)*$/u";
		$this->strError = "Введите корректный ДНК";
	}
}