<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

global $template, $user, $conf, $page, $pwg_loaded_plugins;

if ( (!is_classic_user() and !$conf['ContactForm']['cf_allow_guest']) or !$conf['ContactForm_ready'] )
{
  redirect(get_absolute_root_url());
}

$contact = array();

// +-----------------------------------------------------------------------+
// |                                send email                             |
// +-----------------------------------------------------------------------+
if (isset($_POST['send_mail']))
{
  $contact = array(
    'author' =>  stripslashes(trim($_POST['author'])),
    'email' =>   stripslashes(trim($_POST['email'])),
    'subject' => stripslashes(trim($_POST['subject'])),
    'content' => stripslashes($_POST['content']),
    'send_copy' => isset($_POST['send_copy']),
   );

  $comment_action = send_contact_form($contact, @$_POST['key']);

  switch ($comment_action)
  {
    case 'validate':
      $_SESSION['page_infos'][] = l10n('E-mail sent successfully');
      if (!empty($conf['ContactForm']['cf_redirect_url']))
      {
        redirect($conf['ContactForm']['cf_redirect_url']);
      }
      else
      {
        redirect(CONTACT_FORM_PUBLIC);
      }
      break;
    case 'moderate':
    case 'reject':
      break;
    default:
      trigger_error('Invalid action '.$comment_action, E_USER_WARNING);
  }
}

// +-----------------------------------------------------------------------+
// |                                template                               |
// +-----------------------------------------------------------------------+
if (is_classic_user())
{
  if (empty($contact))
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
  add_event_handler('render_contact_form', 'get_extended_desc');
}

$template->assign(array(
  'contact' => $contact,
  'ContactForm_before' => trigger_change('render_contact_form', nl2br($conf['ContactForm_before'])),
  'ContactForm_after' => trigger_change('render_contact_form', nl2br($conf['ContactForm_after'])),
  'KEY' => get_ephemeral_key(3),
  'CONTACT_FORM_PATH' => CONTACT_FORM_PATH,
  'CONTACT_FORM_ABS_PATH'=> realpath(CONTACT_FORM_PATH).'/',
  'F_ACTION' => CONTACT_FORM_PUBLIC,
  ));

$template->set_template_dir(realpath(CONTACT_FORM_PATH . 'template/'));
$template->set_filename('contactform', 'contact_form.tpl');
$template->assign_var_from_handle('CONTENT', 'contactform');
