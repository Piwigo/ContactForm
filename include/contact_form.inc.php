<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

global $template, $user, $conf, $page, $pwg_loaded_plugins;

if ( (!is_classic_user() and !$conf['ContactForm']['cf_allow_guest']) or !count(get_contact_emails()) )
{
  redirect(get_root_url());
}

// +-----------------------------------------------------------------------+
// |                                send email                             |
// +-----------------------------------------------------------------------+
if (isset($_POST['send_mail']))
{
  $contact = array(
    'author' => trim($_POST['author']),
    'email' => trim($_POST['email']),
    'subject' =>   trim($_POST['subject']),
    'content' =>   $_POST['content'],
   );
  
  $comment_action = send_contact_form($contact, @$_POST['key']);

  switch ($comment_action)
  {
    case 'validate':
      array_push($page['infos'], l10n('E-mail sent successfully'));
      unset($contact);
      break;
    case 'moderate':
    case 'reject':
      break;
    default:
      trigger_error('Invalid comment action '.$comment_action, E_USER_WARNING);
  }
}

// +-----------------------------------------------------------------------+
// |                                template                               |
// +-----------------------------------------------------------------------+
if (is_classic_user())
{
  if (!isset($contact))
  {
    $contact = array(
      'author' => $user['username'],
      'email' => $user['email'],
      'subject' => l10n($conf['ContactForm']['cf_default_subject']),
      'content' => null,
      );
  }
  $contact['is_logged'] = true;
}
if ($conf['ContactForm']['cf_mandatory_mail'])
{
  $contact['mandatory_mail'] = true;
}
if ($conf['ContactForm']['cf_mandatory_name'])
{
  $contact['mandatory_name'] = true;
}

if (!empty($pwg_loaded_plugins['ExtendedDescription']))
{
  add_event_handler('render_contact_form', 'get_user_language_desc');
}

$template->assign(array(
  'contact' => $contact,
  'ContactForm_before' => trigger_event('render_contact_form', $conf['ContactForm_before']),
  'ContactForm_after' => trigger_event('render_contact_form', $conf['ContactForm_after']),
  'KEY' => get_ephemeral_key(3),
  'CONTACT_FORM_PATH' => CONTACT_FORM_PATH,
  'CONTACT_FORM_ABS_PATH'=> dirname(__FILE__).'/../',
  'F_ACTION' => CONTACT_FORM_PUBLIC,
  ));

$template->set_filename('index', dirname(__FILE__).'/../template/contact_form.tpl');

?>