<?php

namespace alimmvc\public_html\models;

use alimmvc\core\models;
use alimmvc\core\registry;

class messageForm extends models
{
	
	public function insertMessage($values)
	{
		$date = date('c');
		$insert = registry::app()->dbform->prepare("INSERT INTO message (`name`,`email`,`date`,`avatar`,`message`) VALUES (:name,:email,'$date',:avatar,:text)");
		$insert->execute($values);
	}

	public function selectMessage($sort = "date", $sc = "DESC")
	{
		$arrSort = ['name', 'email', 'date'];
		$arrSc = ['ASC', 'DESC'];
		if (!in_array($sort, $arrSort)) $sort = "date";
		if (!in_array($sc, $arrSc)) $sc = "DESC";
		$query = registry::app()->dbform->query("SELECT * FROM message WHERE active in (1,2) ORDER BY `$sort` $sc");
		return $query->fetchAll();
	}

	//проверить существует ли аватарка и возвращает url аватарки
	public static function getUrlAvatar($imageName)
	{
		if (!empty($imageName) && file_exists(PATH_DIR_MVC_ASSETS . "/image/avatar/" . $imageName))
		{
			return SITE_URL_ASSETS . "/image/avatar/" . $imageName;
		} else {
			return SITE_URL_ASSETS . "/image/avatar/noavatar.png";
		}
	}
}