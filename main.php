<?php

class Main
{
	public function __construct()
	{
		$this->db = Conf::getDB();
		$this->checkMainTable();
		var_dump('test');
	}

	public function checkMainTable()
	{
		$page = new PageMain($this->db);
		if (!$page->checkTable()) {
			error('Table "' . PageMain::TABLE_NAME . '" doen\'t exist');
		}
	}

}
