<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function contact_form_install()
{
  global $conf, $prefixeTable;
  
  // email table
  $query = '
CREATE TABLE IF NOT EXISTS `'. $prefixeTable .'contact_form` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `active` enum("true","false") NOT NULL DEFAULT "true",
  `group_name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`email`,`group_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
  pwg_query($query);

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
      'cf_mail_type' => 'text/html',
      'cf_redirect_url' => null,
      'cf_theme' => 'dark',
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
      // new params
      $new_conf['cf_must_initialize'] = false;
      $new_conf['cf_default_subject'] = 'A comment on your site';
      $new_conf['cf_mail_type'] = 'text/html';
      $new_conf['cf_redirect_url'] = null;
      
      // move emails to database
      $emails = array();
      foreach ($new_conf['cf_admin_mails'] as $email => $data)
      {
        array_push($emails, array(
          'email' => $email,
          'name' => $data['NAME'],
          'active' => boolean_to_string($data['ACTIVE']),
          ));
      }
      
      $new_conf['cf_must_initialize'] = empty($emails);
      
      mass_inserts(
        $prefixeTable .'contact_form',
        array('name','email','active'),
        $emails
        );
      
      // old params
      unset(
        $new_conf['comment'], $new_conf['cf_redirect_delay'], 
        $new_conf['cf_separator'], $new_conf['cf_separator_length'], 
        $new_conf['cf_admin_mails']
        );
      
      // save config
      $conf['ContactForm_before'] = stripslashes(@$conf['persoformtop']);
      $conf['ContactForm_after'] = stripslashes(@$conf['persoformbottom']);
      
      conf_update_param('ContactForm_before', $conf['ContactForm_before']);
      conf_update_param('ContactForm_after', $conf['ContactForm_after']);
      
      pwg_query('DELETE FROM `'. CONFIG_TABLE .'` WHERE param IN("persoformtop", "persoformbottom") LIMIT 2;');
    }
    
    // new param 2.5.c
    if (!isset($new_conf['cf_theme']))
    {
      $new_conf['cf_theme'] = 'dark';
    }
    
    // save config
    $conf['ContactForm'] = serialize($new_conf);
    conf_update_param('ContactForm', $conf['ContactForm']);
  }
}

?>