<?php
namespace alimmvc\frontend\models\tableQuery;

use alimmvc\core\registry;
use alimmvc\core\models;

class site extends crud
{
	protected $_readIdSite;

	public function __construct($db)
	{
		parent::__construct($db);

		$this->_readIdSite = $this->db->prepare("SELECT id_site FROM `{$this->_table}` WHERE id_user=:id_user AND sitename=:sitename");
	}

	protected function setTable()
	{
		$this->_table = "site";
	}

	protected function setPrepareCreate()
	{
		$this->_create = $this->db->prepare("INSERT INTO `{$this->_table}`(id_user, sitename, title) VALUES(:id_user, :sitename, :title)");
	}
	protected function setPrepareReads()
	{
		$this->_reads = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_user=:id_user");
	}
	protected function setPrepareRead()
	{
		$this->_read = $this->db->prepare("SELECT * FROM `{$this->_table}` WHERE id_user=:id_user AND id_site=:id_site");
	}
	protected function setPrepareUpdate()
	{
		$this->_update = $this->db->prepare("UPDATE `site` SET title=:title WHERE  id_user=:id_user AND id_site=:id_site");
	}
	protected function setPrepareDelete()
	{
		$this->_delete = $this->db->prepare("DELETE FROM `site` WHERE id_user=:id_user AND id_site=:id_site");
	}

	public function readIdSite($data)
	{
		$this->_readIdSite->execute($data);
		$id = $this->_readIdSite->fetch(\PDO::FETCH_ASSOC)['id_site'];
		return (empty($id))?0:$id;
	}
}