<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

// save emails
if (isset($_POST['save_emails']))
{
  $emails = array();
  foreach ($_POST['emails'] as $entry)
  {
    if (isset($entry['delete'])) continue;

    if ( empty($entry['email']) or !email_check_format($entry['email']) )
    {
      $page['errors'][] = l10n('mail address must be like xxx@yyy.eee (example : jack@altern.org)');
    }
    else
    {
      if (empty($entry['name'])) $entry['name'] = $entry['email'];
      if ($entry['group_name'] == -1) $entry['group_name'] = null;

      $emails[] = array(
        'name' => $entry['name'],
        'email' => $entry['email'],
        'group_name' => $entry['group_name'],
        'active' => boolean_to_string(isset($entry['active'])),
        );
    }
  }

  pwg_query('TRUNCATE TABLE `'. CONTACT_FORM_TABLE. '`;');

  mass_inserts(
    CONTACT_FORM_TABLE,
    array('name','email','group_name','active'),
    $emails
    );

  $conf['ContactForm_ready'] = count($emails);

  $page['infos'][] = l10n('Information data registered in database');
}


// display emails
$query = '
SELECT cf.id,
    cf.user_id,
    u.' . $conf['user_fields']['username'] . ' AS name,
    u.' . $conf['user_fields']['email'] . ' AS email
  FROM '. CONTACT_FORM_TABLE .' AS cf
  JOIN '. USERS_TABLE .' AS u
    ON cf.user_id != 0 AND cf.user_id = u.' . $conf['user_fields']['id'] . '
  ORDER BY u.' . $conf['user_fields']['username'] . '
';
$users = query2array($query);

$query = '
SELECT id, name, email
  FROM '. CONTACT_FORM_TABLE .'
  WHERE user_id IS NULL
  ORDER BY name
';
$emails = query2array($query);

$template->assign(array(
  'EMAILS' => $emails,
  'USERS' => $users,
  'CACHE_KEYS' => get_admin_client_cache_keys(array('users')),
  ));

$template->set_filename('contact_form', realpath(CONTACT_FORM_PATH . 'admin/template/emails.tpl'));
