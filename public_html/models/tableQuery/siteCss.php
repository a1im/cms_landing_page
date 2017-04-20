<?php
namespace alimmvc\public_html\models\tableQuery;

use alimmvc\core\registry;
use alimmvc\core\models;

class siteCss extends crud
{
	private $_id_user;
	private $_id_site;

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
	}

	protected function setTable()
	{
		$this->_table = "css_styles";
	}

	protected function setPrepareCreate()
	{
		$this->_create = $this->db->prepare("INSERT INTO `{$this->_table}`(id_site, id_tag, selector, style) VALUES(:id_site, :id_tag, :selector, :style)");
	}
	protected function setPrepareReads()
	{
		$this->_reads = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' ORDER BY id_style ASC");
	}
	protected function setPrepareRead()
	{
		$this->_read = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND id_style=:id_style");
	}
	protected function setPrepareUpdate()
	{
		$this->_update = $this->db->prepare("UPDATE `{$this->_table}` SET style=:style WHERE id_site=:id_site AND id_tag=:id_tag AND selector=:selector");
	}
	protected function setPrepareDelete()
	{
		$this->_delete = $this->db->prepare("DELETE FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND id_style=:id_style");
	}

	// стили у тега
	public function readsTag($data)
	{
		$style = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_site='{$this->_id_site}' AND id_tag=:id_tag");
		$style->execute($data);
		return $style->fetchAll(\PDO::FETCH_ASSOC);
	}
}