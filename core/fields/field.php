<?php

namespace alimmvc\core\fields;

abstract class field implements interfaceField
{
	protected $name;
	protected $type;
	protected $placeholder = "";
	//название поля
	protected $caption;
	//обязательно заполнять или нет
	protected $isRequired = false;
	//значение поля
	protected $value;
	//шаблон для валидации
	protected $pattern = "";
	//описание ошибки
	protected $strError = "Ошибка валидации";
	//если true значит ошибка
	protected $isValidationError = false;
	//блок описания под полем
	protected $isHelpBlock = false;

	//класс и стиль поля
	protected $css_class = "form-control";
	protected $css_style = "";

	public function __construct($name, $caption, $type, $value = "")
	{
		$this->name    = strval($name);
		$this->caption = strval($caption);
		$this->type    = strval($type);
		$this->value   = htmlspecialchars(strval($value));

		$this->init();
	}

	protected function init() {}

	//
	public function setName($val) 
	{
		$this->name = strval($val);
	}

	//
	public function setType($val) 
	{
		$this->type = strval($val);
	}

	//
	public function setPlaceholder($val) 
	{
		$this->placeholder = strval($val);
	}

	//
	public function setCaption($val) 
	{
		$this->caption = strval($val);
	}

	//
	public function setValue($val) 
	{
		$this->value = htmlspecialchars(strval($val));
	}

	//
	public function setPattern($val) 
	{
		$this->pattern = strval($val);
	}

	//
	public function setStrError($val) 
	{
		$this->strError = strval($val);
	}

	//
	public function setCssClass($val) 
	{
		$this->css_class = strval($val);
	}

	//
	public function setCssStyle($val) 
	{
		$this->css_style = strval($val);
	}

	//включить блок информации
	public function trueHelpBlock() 
	{
		$this->isHelpBlock = true;
	}

	//включить ошибки валидации
	public function trueValidationError() 
	{
		$this->isValidationError = true;
	}

	//получить значение для БД
	public function getDbValue()
	{
		return $this->value;
	}

	protected function patternValidation($pattern, $value)
	{
		if (!empty($pattern) && !preg_match($pattern, $value)) 
		{
			return true;
		} else {
			return false;
		}
	}

	//проверка зполнения поля
	public function check()
	{
		//обязательно для заполнения?
		if ($this->isRequired && $this->value == "")
		{
			$this->strError = "Поле обязательно для заполнения!";
			$this->isValidationError = true;
			return true;
		}
		//если поле не обязательно, то проверять шаблон не обязательно
		if (!$this->isRequired && $this->value == "")
		{
			$this->isValidationError = false;
			return false;
		}
		$this->isValidationError = $this->patternValidation($this->pattern, $this->value);

		return $this->isValidationError;
	}

	//Доступ к закрытым и защищенным элементам класса
	public function __get($key)
	{
		if (isset($this->$key)) return $this->$key;
		else throw new \Exception("Член ".__CLASS__."::$key не существует");
	}
}