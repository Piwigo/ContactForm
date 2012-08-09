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
      array_push($emails, array(
        'name' => $entry['name'],
        'email' => $entry['email'],
        'active' => isset($entry['active']),
        ));
    }
  }
  
  $conf['ContactForm']['cf_admin_mails'] = $emails;
  conf_update_param('ContactForm', serialize($conf['ContactForm']));
  array_push($page['infos'], l10n('Information data registered in database'));
}


// display emails
$template->assign('EMAILS', $conf['ContactForm']['cf_admin_mails']);

$template->set_filename('contact_form', dirname(__FILE__).'/template/emails.tpl');

?>
