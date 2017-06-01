<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

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
