<?php

namespace alimmvc\backend\models;

use alimmvc\core\registry;
use alimmvc\core\models;

class crudMessage extends models
{
	public function selectMessages($sort = "date", $sc = "DESC")
	{
		$arrSort = ['name', 'email', 'date'];
		$arrSc = ['ASC', 'DESC'];
		if (!in_array($sort, $arrSort)) $sort = "date";
		if (!in_array($sc, $arrSc)) $sc = "DESC";
		$query = registry::app()->db->query("SELECT * FROM message ORDER BY `$sort` $sc");
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function selectMessage($id)
	{
		$id = intval($id);
		$query = registry::app()->db->query("SELECT * FROM message WHERE id='$id'");
		return $query->fetch(\PDO::FETCH_ASSOC);
	}

	public function setActiveMessage($id, $active = 0)
	{
		//предотвращаем SQL инъекции
		$id = intval($id);
		$active = boolval($active);
		registry::app()->db->prepare("UPDATE `message` SET active='$active' WHERE id='$id'")->execute();
	}

	public function updateMessage($id, $values)
	{
		$id = intval($id);
		registry::app()->db->prepare("UPDATE `message` SET  isadminedit='1', name=:name, email=:email, message=:text WHERE id='$id'")->execute($values);
	}
}