<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

class ContactForm_maintain extends PluginMaintain
{
  private $table;
  
  private $default_conf = array(
    'cf_must_initialize' => true,
    'cf_menu_link' => true,
    'cf_subject_prefix' => '%gallery_title%',
    'cf_default_subject' => 'A comment on your site',
    'cf_allow_guest' => true,
    'cf_mandatory_mail' => true,
    'cf_mandatory_name' => true,
    'cf_mail_type' => 'text/html',
    'cf_redirect_url' => null,
    );
  
  function __construct($id)
  {
    global $prefixeTable;
    
    parent::__construct($id);
    $this->table = $prefixeTable.'contact_form';
  }

  function install($plugin_version, &$errors=array())
  {
    global $conf;

    // email table
  $query = '
CREATE TABLE IF NOT EXISTS `'. $this->table .'` (
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
      conf_update_param('ContactForm', $this->default_conf, true);
      conf_update_param('ContactForm_before', '', true);
      conf_update_param('ContactForm_after', '', true);
    }
    else
    {
      $new_conf = safe_unserialize($conf['ContactForm']);

      // migration 2.4 -> 2.5
      if (!isset($new_conf['cf_must_initialize']))
      {
        // new params
        $new_conf['cf_default_subject'] = 'A comment on your site';
        $new_conf['cf_mail_type'] = 'text/html';
        $new_conf['cf_redirect_url'] = null;

        // move emails to database
        $emails = array();
        foreach ($new_conf['cf_admin_mails'] as $email => $data)
        {
          $emails[] = array(
            'email' => $email,
            'name' => $data['NAME'],
            'active' => boolean_to_string($data['ACTIVE']),
            );
        }

        $new_conf['cf_must_initialize'] = empty($emails);

        mass_inserts(
          $this->table,
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
        conf_update_param('ContactForm', $new_conf, true);
        conf_update_param('ContactForm_before', stripslashes(@$conf['persoformtop']), true);
        conf_update_param('ContactForm_after', stripslashes(@$conf['persoformbottom']), true);
        conf_delete_param(array('persoformtop','persoformbottom'));
      }
    }

    // just in case something went wrong in a previous version
    if (empty($conf['ContactForm_before']))
    {
      conf_update_param('ContactForm_before', '', true);
    }

    if (empty($conf['ContactForm_after']))
    {
      conf_update_param('ContactForm_after', '', true);
    }
  }

  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }

  function uninstall()
  {
    pwg_query('DROP TABLE IF EXISTS `'. $this->table .'`;');

    conf_delete_param(array('ContactForm','ContactForm_before','ContactForm_after'));
  }
}
