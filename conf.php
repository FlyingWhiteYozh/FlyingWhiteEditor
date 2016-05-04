<?php

class Conf
{
    static function ROOT()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/fwe/';
    }

    //true if allowed
    static function accessCheck() 
    {
        return preg_match('{^91\.244\.169\.\d+$}', $_SERVER ['REMOTE_ADDR']);
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

//Отключаем вааалшебные кавычки. На всякий случай.
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}