<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

class ContactForm_maintain extends PluginMaintain
{
  private $installed = false;

  function install($plugin_version, &$errors=array())
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
      $conf['ContactForm'] = serialize(array(
        'cf_must_initialize' => true,
        'cf_menu_link' => true,
        'cf_subject_prefix' => '%gallery_title%',
        'cf_default_subject' => 'A comment on your site',
        'cf_allow_guest' => true,
        'cf_mandatory_mail' => true,
        'cf_mandatory_name' => true,
        'cf_mail_type' => 'text/html',
        'cf_redirect_url' => null,
        ));

      $conf['ContactForm_before'] = null;
      $conf['ContactForm_after'] = null;

      conf_update_param('ContactForm', $conf['ContactForm']);
      conf_update_param('ContactForm_before', $conf['ContactForm_before']);
      conf_update_param('ContactForm_after', $conf['ContactForm_after']);
    }
    else
    {
      $new_conf = is_string($conf['ContactForm']) ? unserialize($conf['ContactForm']) : $conf['ContactForm'];

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

        conf_delete_param(array('persoformtop','persoformbottom'));
      }

      // save config
      $conf['ContactForm'] = serialize($new_conf);
      conf_update_param('ContactForm', $conf['ContactForm']);
    }

    // just in case something went wrong in a previous version
    if (empty($conf['ContactForm_before']))
    {
      $conf['ContactForm_before'] = null;
      conf_update_param('ContactForm_before', $conf['ContactForm_before']);
    }

    if (empty($conf['ContactForm_after']))
    {
      $conf['ContactForm_after'] = null;
      conf_update_param('ContactForm_after', $conf['ContactForm_after']);
    }
  }

  function activate($plugin_version, &$errors=array())
  {
    if (!$this->installed)
    {
      $this->install($plugin_version, $errors);
    }
  }

  function deactivate()
  {
  }


  function uninstall()
  {
    global $prefixeTable;

    pwg_query('DROP TABLE IF EXISTS `'. $prefixeTable .'contact_form`;');

    conf_delete_param(array('ContactForm','ContactForm_before','ContactForm_after'));
  }
}
