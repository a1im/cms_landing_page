<?php
namespace alimmvc\public_html\models\tableQuery;

use alimmvc\core\registry;
use alimmvc\core\models;

abstract class crud extends models
{
	// база данных
	protected $_db;
	// название таблицы
	protected $_table;

	protected $_create;
	protected $_reads;
	protected $_read;
	protected $_update;
	protected $_delete;

	public function __construct($db)
	{
		$this->db = $db;
		$this->setTable();
		$this->setPrepareCreate();
		$this->setPrepareReads();
		$this->setPrepareRead();
		$this->setPrepareUpdate();
		$this->setPrepareDelete();
	}

	public function create($data)
	{
		$result = $this->_create->execute($data);
		return ($result == 1)?true:false;
	}

	public function reads($data = [])
	{
		$this->_reads->execute($data);
		return $this->_reads->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function read($data)
	{
		$this->_read->execute($data);
		return $this->_read->fetch(\PDO::FETCH_ASSOC);
	}

	public function update($data)
	{
		$result = $this->_update->execute($data);
		return ($result == 1)?true:false;
	}

	public function delete($data)
	{
		$result = $this->_delete->execute($data);
		return ($result == 1)?true:false;
	}

	// разбить массив на ключи для запроса
	protected function getKey($data)
	{
		$result = "";
		foreach ($data as $key => $value) 
		{
			$result .= "$key=:$key, ";
		}
		return empty($result)?"":preg_replace("|, $|iu", "", $result);
	}

	protected abstract function setTable();
	protected abstract function setPrepareCreate();
	protected abstract function setPrepareReads();
	protected abstract function setPrepareRead();
	protected abstract function setPrepareUpdate();
	protected abstract function setPrepareDelete();

}