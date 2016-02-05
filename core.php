<?php
namespace FWE;

ob_start('\fwe\output_callback');

function output_callback($content)
{
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') return false;

	return str_replace('{content}', $content, file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/fwe/modal.html'));
}

function error($message)
{
	die('<div class="alert alert-danger">' . $message . '</div>');
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

require 'page.php';
$allowedActions = array('get', 'set');
$errors = array();

if(empty($_REQUEST['id']))  $errors[] = 'ID isn\'t specified';

if(empty($_REQUEST['a']) || !in_array($_REQUEST['a'], $allowedActions))  $errors[] = 'Action is not allowed';

if(count($errors)) error(implode('<br>'.PHP_EOL, $errors));	

$page = new Page($_REQUEST['id']);

switch ($_REQUEST['a']) {
	case 'get':
		$page->get();
		break;
	case 'set':
		$page->update($_REQUEST['data']);
		break;
	
	default:
		error('Not allowed');
		break;
}

