<?php

require dirname(__FILE__).'/conf.php';

if (!Conf::checkRights())
	die('access denied');

ob_start('fwe_output_callback');

function fwe_output_callback($content)
{
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') return false;

	return str_replace('{content}', $content, file_get_contents(Conf::ROOT() . 'modal.html'));
}

function error($message)
{
	die('<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>');
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

