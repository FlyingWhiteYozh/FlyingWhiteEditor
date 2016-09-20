<?php
require_once(Conf::ROOT() . '/types.php');
class Conf
{
    public static function ROOT()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/fwe/';
    }

    //true if allowed
    public static function accessCheck() 
    {
        return preg_match('{^(91\.244\.169|77\.93\.126)\.\d+$}', $_SERVER ['REMOTE_ADDR']);
    }

    public static function isWIN1251()
    {
        return false;
    }
}

function prepare($query)
{
    static $db = NULL;
    if($db === NULL) {
        $driver   = 'mysql';
        $host     = 'localhost';
        $dbname   = '';
        $dns      = "$driver:host=$host;dbname=$dbname";
        $password = '';
        $username = '';
        try {
            $db = new PDO($dns, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        } catch (PDOException $e) {
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