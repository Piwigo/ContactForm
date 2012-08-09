<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

global $template, $page;

$page['tab'] = (isset($_GET['tab'])) ? $_GET['tab'] : 'config';

// tabsheet
include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');    
$tabsheet = new tabsheet();
$tabsheet->add('config', l10n('Configuration'), CONTACT_FORM_ADMIN . '-config');
$tabsheet->add('emails', l10n('E-mails'), CONTACT_FORM_ADMIN . '-emails');
$tabsheet->select($page['tab']);
$tabsheet->assign();

// include page
include(CONTACT_FORM_PATH . 'admin/' . $page['tab'] . '.php');

if (!count(get_contact_emails()))
{
  array_push($page['errors'], l10n('No active email address'));
}

// template
$template->assign(array(
  'CONTACT_FORM_PATH' => CONTACT_FORM_PATH,
  'CONTACT_FORM_ADMIN' => CONTACT_FORM_ADMIN,
  ));

$template->assign_var_from_handle('ADMIN_CONTENT', 'contact_form');

?>