<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function contact_form_install()
{
  global $conf;

  // configuration
  if (empty($conf['ContactForm']))
  {
    $contact_form_default_config = serialize(array(
      'cf_must_initialize' => true,
      'cf_menu_link' => true,
      'cf_subject_prefix' => '%gallery_title%',
      'cf_default_subject' => 'A comment on your site',
      'cf_allow_guest' => true,
      'cf_mandatory_mail' => true,
      'cf_mandatory_name' => true,
      'cf_redirect_delay' => 5,
      'cf_mail_type' => 'text/html',
      'cf_admin_mails' => array(),
      ));
    
    conf_update_param('ContactForm', $contact_form_default_config);
    conf_update_param('ContactForm_before', null);
    conf_update_param('ContactForm_after', null);
    
    $conf['ContactForm'] = $contact_form_default_config;
    $conf['ContactForm_before'] = null;
    $conf['ContactForm_after'] = null;
  }
  else
  {
    $new_conf = is_string($conf['ContactForm']) ? unserialize($conf['ContactForm']) : $conf['ContactForm'];
    
    // migration 2.4 -> 2.5
    if (!isset($new_conf['cf_must_initialize']))
    {
      $new_conf['cf_must_initialize'] = false;
      $new_conf['cf_default_subject'] = 'A comment on your site';
      $new_conf['cf_mail_type'] = 'text/html';
      unset($new_conf['comment'], $new_conf['cf_redirect_delay'], $new_conf['cf_separator'], $new_conf['cf_separator_length']);
      
      foreach ($new_conf['cf_admin_mails'] as $email => $data)
      {
        $new_conf['cf_admin_mails'][] = array(
          'email' => $email,
          'name' => $data['NAME'],
          'active' => $data['ACTIVE'],
          );
        unset($new_conf['cf_admin_mails'][ $email ]);
      }
      
      $conf['ContactForm'] = serialize($new_conf);
      $conf['ContactForm_before'] = stripslashes($conf['persoformtop']);
      $conf['ContactForm_after'] = stripslashes($conf['persoformbottom']);
      
      conf_update_param('ContactForm', $conf['ContactForm']);
      conf_update_param('ContactForm_before', $conf['ContactForm_before']);
      conf_update_param('ContactForm_after', $conf['ContactForm_after']);
      
      pwg_query('DELETE FROM `'. CONFIG_TABLE .'` WHERE param IN("persoformtop", "persoformbottom") LIMIT 2;');
    }
  }
}

?>