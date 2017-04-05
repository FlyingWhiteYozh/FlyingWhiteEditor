<?php

class FWE_Main
{
    public function __construct()
    {
        $this->db = FWE_Conf::getDB();

        $action = false;
        if (isset($_REQUEST['s'])) {
            $action = 'action' . $_REQUEST['s'];
        }

        if ($action) {
            if (!method_exists($this, $action)) {
                fwe_error('No such method');
            }

            $this->$action();
        }

        $this->checkMainTable();
    }

    public function checkMainTable()
    {
        $page = new FWE_PageMain($this->db);
        if (!$page->checkTable()) {
            fwe_error('Table "' . FWE_PageMain::TABLE_NAME . '" doesn\'t exist. ' . fwe_html_link('main&s=CreateTable', 'Create?'));
        }
    }

    public function actionCreateTable()
    {
        $page = new FWE_PageMain($this->db);
        $page->createTable();
        fwe_success('Table was successfully created');
    }

}
