<?php

class Main
{
    public function __construct()
    {
        $this->db = Conf::getDB();

        $action = false;
        if (isset($_REQUEST['s'])) {
            $action = 'action' . $_REQUEST['s'];
        }

        if ($action) {
            if (!method_exists($this, $action)) {
                error('No such method');
            }

            $this->$action();
        }

        $this->checkMainTable();
    }

    public function checkMainTable()
    {
        $page = new PageMain($this->db);
        if (!$page->checkTable()) {
            error('Table "' . PageMain::TABLE_NAME . '" doesn\'t exist. ' . html_link('main&s=CreateTable', 'Create?'));
        }
    }

    public function actionCreateTable()
    {
        $page = new PageMain($this->db);
        $page->createTable();
        success('Table was successfully created');
    }

}
