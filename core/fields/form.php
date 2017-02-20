<?php

namespace alimmvc\core\fields;

class form implements interfaceField
{
	//Внутренние элементы
	protected $fields;
	//
	protected $action;
	//класс и стиль поля
	protected $css_class = "";
	protected $css_style = "";

	public function __construct(array $fields = array(), $action = "")
	{
		$this->action = strval($action);
		//проверяем являются ли элементы массива экземплярами класса field
		foreach ($fields as $key => $obj) 
		{
			if (!is_subclass_of($obj,"alimmvc\\core\\fields\\field"))
			{
				throw new \Exception("\"$key\" не является элементом управления");
			}
		}
		$this->fields = $fields;
	}

	public function getHtml()
	{
		$enctype = "";

		if (!empty($this->fields))
		{
			foreach ($this->fields as $obj) 
			{
				// echo $obj->type . "<br>";
				//если есть поле file включаем эту строку
				if ($obj->type == "file") 
				{
					$enctype = "enctype='multipart/form-data'";
				}
			}
		}

		$style = (!empty($this->css_style))?"style=\"{$this->css_style}\"":"";
		$class = (!empty($this->css_class))?"class=\"{$this->css_class}\"":"";
		$action = (!empty($this->action))?"action=\"{$this->action}1\"":"";

		$tag = "<form $action name='form' $enctype method='POST' $style $class>";

		if (!empty($this->fields))
		{
			foreach($this->fields as $obj)
			{
				//получаем название поля и его HTML представление
				$getHtml = $obj->getHtml();
				// //если массив
				// if (is_array($getHtml)) 
				// {
				// 	list($caption, $getTag) = $getHtml;

				// 	if (is_array($getTag)) implode("<br>",$getTag);

				// 	$tag .= "$caption";
				// 	$tag .= "$getTag";
				// } else 
				// {
				$tag .= $getHtml;
				// }
			}
		}

		$tag .= "</form>";

		return $tag;
	}

	public function check()
	{
		$isError = false;
		//вызываем check во всех полях
		if (!empty($this->fields))
		{
			foreach($this->fields as $obj)
			{
				if ($obj->check()) $isError = true;
			}
		}

		return $isError;
	}

	public function setAction($val)
	{
		$this->action = strval($val);
	}

	//присвоить fields
	public function setFields(array $fields) 
	{
		foreach ($fields as $key => $obj) 
		{
			if (!is_subclass_of($obj,"Field"))
			{
				throw new \Exception("\"$key\" не является элементом управления");
			}
		}
		$this->fields = $fields;
	}

	//получить значение для БД
	public function getDbValue()
	{
		$values = array();
		//получаем массив значений для БД
		if (!empty($this->fields))
		{
			foreach($this->fields as $obj)
			{
				if ($obj->getDbValue() !== false) $values[$obj->name] = $obj->getDbValue();
			}
		}

		return $values;
	}

	//добавляем тег в fields
	public function addFieldsFirst(Field $tag) 
	{
		array_unshift($this->fields, $tag);
	}

	//добавляем тег в fields
	public function addFields(Field $tag) 
	{
		$this->fields[] = $tag;
	}

	public function __get($key)
	{
		if (isset($this->$key)) return $this->$key;
		else throw new \Exception("Член ".__CLASS__."::$key не существует");
	}
}