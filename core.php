<?php

require dirname(__FILE__) . '/conf.php';

if (!Conf::accessCheck()) {
    die('access denied');
}

ob_start('fwe_output_callback');

function fwe_output_callback($content)
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        return false;
    }

    return str_replace('{content}', $content, file_get_contents(Conf::ROOT() . 'modal.html'));
}

function error($message)
{
    die('<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>');
}

function convertUTFtoWIN1251(&$var)
{
    if (is_string($var)) {
        $var = mb_convert_encoding($var, 'windows-1251', 'UTF-8');
    }

    if (is_array($var)) {
        foreach ($var as &$value) {
            convertUTFtoWIN1251($value);
        }
    }

}

require 'page.php';
$allowedActions = array('get', 'set');
$errors         = array();

if (empty($_REQUEST['a']) || !in_array($_REQUEST['a'], $allowedActions)) {
    $errors[] = 'Action is not allowed';
}

$action = $_REQUEST['a'];

if ($action == 'set') {
    if (!isset($_REQUEST['data'])) {
        error('You must provide "data" for this method');
    }

    $data = $_REQUEST['data'];
}

if (Conf::isWIN1251()) {
    convertUTFtoWIN1251($data);
}

if (empty($_REQUEST['id'])) {
    if ($action == 'set') {
        $pages = array();
        foreach ($_REQUEST['data'] as $pageData) {
            if (isset($pageData['id'])) {
                $pages[] = new Page($pageData['id']);
            } else {
                $errors[] = 'ID isn\'t specified';
            }

        }
    } else {
        $errors[] = 'ID isn\'t specified';
    }

}

if (count($errors)) {
    error(implode('<br>' . PHP_EOL, $errors));
}

if (!isset($pages)) {
    $ids   = (array) $_REQUEST['id'];
    $pages = array();
    foreach ($ids as $id) {
        $pages[] = new Page($id);
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

    default:
        error('Not allowed');
        break;
}
