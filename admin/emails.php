<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

// save emails
if (isset($_POST['save_emails']))
{
  $emails = array();
  foreach ($_POST['emails'] as $entry)
  {
    if (isset($entry['delete'])) continue;
    
    if ( empty($entry['email']) or !check_email_validity($entry['email']) )
    {
      array_push($page['errors'], l10n('mail address must be like xxx@yyy.eee (example : jack@altern.org)'));
    }
    else
    {
      if ($entry['group_name'] == -1) $entry['group_name'] = null;
      
      array_push($emails, array(
        'name' => $entry['name'],
        'email' => $entry['email'],
        'group_name' => $entry['group_name'],
        'active' => boolean_to_string(isset($entry['active'])),
        ));
    }
  }
  
  pwg_query('TRUNCATE TABLE `'. CONTACT_FORM_TABLE. '`;');
  
  mass_inserts(
    CONTACT_FORM_TABLE,
    array('name','email','group_name','active'),
    $emails
    );
  
  array_push($page['infos'], l10n('Information data registered in database'));
}


// display emails
$query = '
SELECT *
  FROM '. CONTACT_FORM_TABLE .'
  ORDER BY
    group_name ASC,
    name ASC
';
$result = pwg_query($query);

$emails = $groups = array();
while ($data = pwg_db_fetch_assoc($result))
{
  $data['active'] = get_boolean($data['active']);
  array_push($emails, $data);
  if (!empty($data['group_name']))
  {
    array_push($groups, $data['group_name']);
  }
}

$template->assign(array(
  'EMAILS' => $emails,
  'GROUPS' => array_unique($groups),
  ));

$template->set_filename('contact_form', realpath(CONTACT_FORM_PATH . 'admin/template/emails.tpl'));

?>
