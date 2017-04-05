<?php

class FWE_PageMain
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

    public function createTable()
    {
        $this->db->query('CREATE TABLE `fwe_page` (
		  `url` varchar(255) NOT NULL,
		  `revision` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  `title` text NOT NULL,
		  `description` text NOT NULL,
		  `keywords` text NOT NULL,
		  `h1` text NOT NULL,
		  `text_top` text NOT NULL,
		  `text_bottom` text NOT NULL,
		  PRIMARY KEY  (`url`),
		  KEY `revision` (`revision`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

}
