<?php
namespace alimmvc\frontend\models\tableQuery;

use alimmvc\core\registry;
use alimmvc\core\models;

class siteTags extends crud
{
	private $_id_user;
	private $_id_site;

	// инкремент индекса
	protected $_updateIncIndex;
	// декремент индекса
	protected $_updateDecIndex;
	// SQL запрос узнать последний номер id_tag у определенного id_site
	protected $_readLastIdTag;
	// SQL запрос узнать последний номер index_tag у определенного id_site, parent_tag
	protected $_readLastIndexTeg;
	// SQL запрос узнать позиции тегов в родительском теге
	protected $_readPosTegs;

	public function __construct($db, $id_user, $id_site)
	{
		$this->_id_user = intval($id_user);
		$this->_id_site = intval($id_site);
		parent::__construct($db);
		// проверка на принадлежность сайта пользователю
		$isSiteUser = registry::app()->db->query("SELECT * FROM `site` WHERE id_user='{$this->_id_user}' AND id_site='{$this->_id_site}'")->fetch(\PDO::FETCH_ASSOC);
		if (empty($isSiteUser))
		{
			debug("error: нет сайта");
		}

		$this->_updateIncIndex = $this->db->prepare("UPDATE `{$this->_table}` SET index_tag=index_tag+1 WHERE id_site='{$this->_id_site}' AND parent_tag=:parent_tag AND index_tag>=:index_tag_new AND index_tag<:index_tag_old AND id_tag!=:id_tag");
		$this->_updateDecIndex = $this->db->prepare("UPDATE `{$this->_table}` SET index_tag=index_tag-1 WHERE id_site='{$this->_id_site}' AND parent_tag=:parent_tag AND index_tag>:index_tag_old AND index_tag<=:index_tag_new AND id_tag!=:id_tag");
		$this->_readLastIdTag = registry::app()->db->prepare("SELECT id_tag FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' ORDER BY id_tag DESC LIMIT 1");
		$this->_readLastIndexTeg = registry::app()->db->prepare("SELECT MAX(index_tag) AS max_index FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND parent_tag=:parent_tag");
		$this->_readPosTegs = registry::app()->db->prepare("SELECT position_in_text FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND parent_tag=:parent_tag ORDER BY position_in_text ASC");

	}

	protected function setTable()
	{
		$this->_table = "html_tags";
	}

	protected function setPrepareCreate()
	{
		$this->_create = $this->db->prepare("INSERT INTO `{$this->_table}`(id_tag, id_site, parent_tag, index_tag, position_in_text, name, content, selectors) VALUES(:id_tag, :id_site, :parent_tag, :index_tag, :position_in_text, :name, :content, :selectors)");
	}
	protected function setPrepareReads()
	{
		$this->_reads = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' ORDER BY parent_tag ASC, index_tag ASC");
	}
	protected function setPrepareRead()
	{
		$this->_read = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND id_tag=:id_tag");
	}
	protected function setPrepareUpdate()
	{
		// $this->_update = $this->db->prepare("UPDATE `{$this->_table}` SET parent_tag=:parent_tag, index_tag=:index_tag, position_in_text=:position_in_text, name=:name, content=:content, selectors=:selectors WHERE id_site='{$this->_id_site}' AND id_tag=:id_tag");
	}
	protected function setPrepareDelete()
	{
		$this->_delete = $this->db->prepare("DELETE FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND id_tag=:id_tag");
	}

	// создать тег
	public function create($data)
	{
		$result = parent::create($data);
		// если удачно, то сдвиг индекса на +1 которые стоят >= него
		if ($result)
		{
			$result = $this->updateIncIndex([
				'parent_tag' => $data['parent_tag'], 
				'index_tag_new' => $data['index_tag'], 
				'index_tag_old' => 65535, 
				'id_tag' => $data['id_tag'],
				]);
		}
		return $result;
	}

	public function updateSelectorsTag($data)
	{
		$this->_update = $this->db->prepare("UPDATE `{$this->_table}` SET selectors=:selectors WHERE id_site='{$this->_id_site}' AND id_tag=:id_tag");
		$result = $this->_update->execute($data);
		return ($result == 1)?true:false;
	}

	public function updateIndexTag($data)
	{
		$tag = $this->read(['id_tag' => $data['id_tag']]);
		$index_tag_new = $data['index_tag'];
		$index_tag_old = $tag['index_tag'];
		// если сменили родителя
		if ($data['parent_tag'] != $tag['parent_tag'])
		{
			$result = $this->updateDecIndex([
				'parent_tag' => $tag['parent_tag'], 
				'index_tag_new' => 65535, 
				'index_tag_old' => $index_tag_old, 
				'id_tag' => $data['id_tag'],
				]);
			$index_tag_old = 65535;
		}

		if ($index_tag_old > $index_tag_new)
		{
			$result = $this->updateIncIndex([
				'parent_tag' => $data['parent_tag'], 
				'index_tag_new' => $index_tag_new, 
				'index_tag_old' => $index_tag_old, 
				'id_tag' => $data['id_tag'],
				]);
		}
		if ($index_tag_old < $index_tag_new)
		{
			$result = $this->updateDecIndex([
				'parent_tag' => $data['parent_tag'], 
				'index_tag_new' => $index_tag_new, 
				'index_tag_old' => $index_tag_old, 
				'id_tag' => $data['id_tag'],
				]);
		}

		$this->_update = $this->db->prepare("UPDATE `{$this->_table}` SET index_tag=:index_tag, parent_tag=:parent_tag WHERE id_site='{$this->_id_site}' AND id_tag=:id_tag");
		$result = $this->_update->execute($data);
		return ($result == 1)?true:false;
	}

	public function updateIncIndex($data)
	{
		$result = $this->_updateIncIndex->execute($data);
		return ($result == 1)?true:false;
	}

	public function updateDecIndex($data)
	{
		$result = $this->_updateDecIndex->execute($data);
		return ($result == 1)?true:false;
	}

	// узнать id последнего тега у сайта
	public function readLastIdTag()
	{
		$this->_readLastIdTag->execute();
		$id = $this->_readLastIdTag->fetch(\PDO::FETCH_ASSOC)['id_tag'];
		return (empty($id))?0:$id;
	}

	// узнать id последнего тега у сайта
	public function readLastIndexTeg($parent_tag)
	{
		$this->_readLastIndexTeg->execute(['parent_tag' => $parent_tag]);
		$id = $this->_readLastIndexTeg->fetch(\PDO::FETCH_ASSOC)['max_index'];
		return (empty($id))?0:$id;
	}

	// узнать позиции тегов в родительском теге
	public function readPosTegs($parent_tag)
	{
		$this->_readPosTegs->execute(['parent_tag' => $parent_tag]);
		return $this->_readPosTegs->fetchAll(\PDO::FETCH_ASSOC);
	}
}