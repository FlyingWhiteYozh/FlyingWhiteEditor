<?php

require dirname(__FILE__) . '/conf.php';

if (!FWE_Conf::accessCheck()) {
    die('access denied');
}

ob_start('fwe_output_callback');

function fwe_output_callback($content)
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        return false;
    }

    return str_replace('{content}', $content, file_get_contents(FWE_Conf::ROOT() . '/modal.html'));
}

function fwe_error($message)
{
    die('<div class="alert alert-danger">' . ($message) . '</div>');
}

function fwe_success($message)
{
    echo '<div class="alert alert-success">' . $message . '</div>';
}

function fwe_html_link($href, $name)
{
	return '<a href="/fwe/core.php?a=' . $href . '">' . $name . '</a>';
}

function fwe_convertUTFtoWIN1251(&$var)
{
    if (is_string($var)) {
        $var = mb_convert_encoding($var, 'windows-1251', 'UTF-8');
    }

    if (is_array($var)) {
        foreach ($var as &$value) {
            fwe_convertUTFtoWIN1251($value);
        }
    }

}


require FWE_Conf::ROOT() . '/page.php';
require FWE_Conf::ROOT() . '/main.php';

$allowedActions = array('main', 'get', 'set');
$errors         = array();

if (empty($_REQUEST['a'])) {
    $_REQUEST['a'] = 'main';
}

$action = $_REQUEST['a'];

if (!in_array($action, $allowedActions)) {
    $errors[] = 'Action is not allowed';
}

if ($action == 'set') {
    if (!isset($_REQUEST['data'])) {
        fwe_error('You must provide "data" for this method');
    }

    $data = $_REQUEST['data'];
}

if (FWE_Conf::isWIN1251()) {
    fwe_convertUTFtoWIN1251($data);
}

if (empty($_REQUEST['id'])) {
    if ($action == 'set') {
        $pages = array();
        foreach ($_REQUEST['data'] as $pageData) {
            if (isset($pageData['id'])) {
                $pages[] = new FWE_Page($pageData['id']);
            } else {
                $errors[] = 'ID isn\'t specified';
            }

        }
    } elseif ($action == 'get') {
        $errors[] = 'ID isn\'t specified';
    }

}

if (count($errors)) {
    fwe_error(implode('<br>' . PHP_EOL, $errors));
}

if (!isset($pages) && !empty($_REQUEST['id']) && is_array($_REQUEST['id'])) {
    $ids   = (array) $_REQUEST['id'];
    $pages = array();
    foreach ($ids as $id) {
        $pages[] = new FWE_Page($id);
    }

}

switch ($action) {
    case 'get':
        foreach ($pages as $page) {
            $page->get();
        }

        break;
    case 'set':
        foreach ($pages as $page) {
            $page->update($data[$page->idToString()]);
        }

        break;

    case 'main':
        $main = new FWE_Main;
        break;

    default:
        fwe_error('Not allowed');
        break;
}
