<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

global $template, $user, $conf, $page, $pwg_loaded_plugins;

if ( (!is_classic_user() and !$conf['ContactForm']['cf_allow_guest']) or !$conf['ContactForm']['cf_ready'] )
{
  redirect(get_absolute_root_url());
}

// +-----------------------------------------------------------------------+
// |                                send email                             |
// +-----------------------------------------------------------------------+
if (isset($_POST['send_mail']))
{
  $contact = array(
    'author' =>  trim($_POST['author']),
    'email' =>   trim($_POST['email']),
    'group' =>   @$_POST['group'],
    'subject' => trim($_POST['subject']),
    'content' => $_POST['content'],
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
  if (!isset($contact))
  {
    $contact = array(
      'author' => $user['username'],
      'email' => $user['email'],
      'group' => null,
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

$query = '
SELECT DISTINCT group_name
  FROM '. CONTACT_FORM_TABLE .'
  ORDER BY group_name
;';
$result = pwg_query($query);

$groups = array();
while ($data = pwg_db_fetch_assoc($result))
{
  $groups[ $data['group_name'] ] = !empty($data['group_name']) ? l10n($data['group_name']) : l10n('Default');
}

if (count($groups) > 1)
{
  $template->assign('GROUPS', $groups);
}

$template->assign(array(
  'contact' => $contact,
  'ContactForm_before' => trigger_event('render_contact_form', nl2br($conf['ContactForm_before'])),
  'ContactForm_after' => trigger_event('render_contact_form', nl2br($conf['ContactForm_after'])),
  'KEY' => get_ephemeral_key(3),
  'CONTACT_FORM_PATH' => CONTACT_FORM_PATH,
  'CONTACT_FORM_ABS_PATH'=> realpath(CONTACT_FORM_PATH).'/',
  'F_ACTION' => CONTACT_FORM_PUBLIC,
  ));

$template->set_filename('index', realpath(CONTACT_FORM_PATH . 'template/contact_form.tpl'));

?>