<?php

namespace alimmvc\core\fields;

interface interfaceField
{
	//проверка зполнения поля
	public function check();

	//выводит тег элемента с всеми параметрами
	public function getHtml();
}