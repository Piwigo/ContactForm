<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

// save config
if (isset($_POST['save_config']))
{
  $conf['ContactForm'] = array(
    'cf_must_initialize' =>   false,
    'cf_menu_link' =>         isset($_POST['cf_menu_link']),
    'cf_subject_prefix' =>    trim($_POST['cf_subject_prefix']),
    'cf_default_subject' =>   trim($_POST['cf_default_subject']),
    'cf_allow_guest' =>       isset($_POST['cf_allow_guest']),
    'cf_mandatory_mail' =>    isset($_POST['cf_mandatory_mail']),
    'cf_mandatory_name' =>    isset($_POST['cf_mandatory_name']),
    'cf_mail_type' =>         $_POST['cf_mail_type'],
    'cf_redirect_url' =>      $_POST['cf_redirect_url'],
    );
  $conf['ContactForm_before'] = $_POST['cf_before'];
  $conf['ContactForm_after'] = $_POST['cf_after'];
  
  conf_update_param('ContactForm', serialize($conf['ContactForm']));
  conf_update_param('ContactForm_before', $conf['ContactForm_before']);
  conf_update_param('ContactForm_after', $conf['ContactForm_after']);
  
  array_push($page['infos'], l10n('Information data registered in database'));
}


// display config
$template->assign($conf['ContactForm']);
$template->assign(array(
  'cf_before' => stripslashes($conf['ContactForm_before']),
  'cf_after' => stripslashes($conf['ContactForm_after']),
  ));

$template->set_filename('contact_form', realpath(CONTACT_FORM_PATH . 'admin/template/config.tpl'));

?>