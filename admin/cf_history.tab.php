<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
check_status(ACCESS_ADMINISTRATOR);

$errors=array();
$history = cf_get_history_list(CF_PATH.CF_CHANGELOG, $errors);
$template->assign('CF_HISTORY', $history); 
$template->assign('CF_HISTORY_ERROR', $errors); 
?>