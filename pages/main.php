<?php

class PageMain
{
	const TABLE_NAME = 'fwe_page';

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function checkTable()
	{
		return (bool) $this->db->query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = \'' . self::TABLE_NAME . '\'')->fetch();
	}
}