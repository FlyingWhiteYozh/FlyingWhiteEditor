<?php

class FWE_PageMain
{
    const TABLE_NAME = 'fwe_page';
    private $page = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($uri)
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . self::TABLE_NAME . ' WHERE uri = ?');
        if ($stmt->execute(array($uri))) {
            $this->page = $stmt->fetch(PDO::FETCH_OBJ);
        }
        return (bool) $this->page;
    }

    public function __get($name)
    {
        return $this->page->$$name;
    }

    public function checkTable()
    {
        return (bool) $this->db->query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = \'' . self::TABLE_NAME . '\'')->fetch();
    }

    public function getTitle()
    {
        return $this->page->title;
    }

    public function getDescription()
    {
        return $this->page->description;
    }

    public function getKeywords()
    {
        return $this->page->keywords;
    }

    public function getH1()
    {
        return $this->page->h1;
    }

    public function getTextTop()
    {
        return $this->page->text_top;
    }

    public function getTextBottom()
    {
        return $this->page->text_bottom;
    }

    public function createTable()
    {
        $this->db->query('CREATE TABLE `fwe_page` (
		  `uri` varchar(255) NOT NULL,
		  `revision` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  `title` text NOT NULL,
		  `description` text NOT NULL,
		  `keywords` text NOT NULL,
		  `h1` text NOT NULL,
		  `text_top` text NOT NULL,
		  `text_bottom` text NOT NULL,
		  PRIMARY KEY  (`uri`),
		  KEY `revision` (`revision`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

}
