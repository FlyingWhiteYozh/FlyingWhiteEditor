<?php
require_once FWE_Conf::ROOT() . '/types.php';
class FWE_Conf
{
    public static function ROOT()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/fwe';
    }

    //true if allowed
    public static function accessCheck()
    {
        return preg_match('{^(91\.244\.169|77\.93\.126)\.\d+$}', $_SERVER['REMOTE_ADDR']);
    }

    public static function isWIN1251()
    {
        return false;
    }

    public static function getDB()
    {
        $driver   = 'mysql';
        $host     = 'localhost';
        $dbname   = '';
        $dns      = "$driver:host=$host;dbname=$dbname";
        $password = '';
        $username = '';
        try {
            $db = new PDO($dns, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            return $db;
        } catch (PDOException $e) {
            fwe_error('DB connection error: ' . $e->getMessage());
        }
    }
}

function prepare($query)
{
    static $db = null;
    if ($db === null) {
        $db = FWE_Conf::getDB();
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
                $process[]                       = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}
