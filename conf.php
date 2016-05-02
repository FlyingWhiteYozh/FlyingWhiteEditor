<?php

class Conf
{
    static function ROOT()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/fwe/';
    }
}

function prepare($query)
{
    static $db = NULL;
    if($db === NULL) {
        $ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
        // var_dump($ini);
        $dns = 'mysql:host=' . $ini['db_server'] . ';dbname=' . $ini['db_name'];
        try {
            $db = new \PDO($dns, $ini['db_user'], $ini['db_password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

    }
    return $db->prepare($query);
}