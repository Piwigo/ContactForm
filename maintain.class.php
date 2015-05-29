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
  pwg_query('
CREATE TABLE IF NOT EXISTS `'. $this->table .'` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned DEFAULT 0,
  `name` varchar(128) NULL DEFAULT NULL,
  `email` varchar(128) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
;');

    // configuration
    if (empty($conf['ContactForm']))
    {
      conf_update_param('ContactForm', $this->default_conf, true);
    }
    else
    {
      $new_conf = safe_unserialize($conf['ContactForm']);

      // migration 2.4 -> 2.5
      if (!isset($new_conf['cf_must_initialize']))
      {
        $this->migrate_25($new_conf);
      }
    
      // migration 2.7 -> 2.8
      $result = pwg_query('SHOW COLUMNS FROM `' . $this->table . '` LIKE "user_id";');
      if (!pwg_db_num_rows($result))
      {
        $this->migrate_28($new_conf);
      }
    }

    if (!array_key_exists('ContactForm_before', $conf))
    {
      conf_update_param('ContactForm_before', '', true);
    }

    if (!array_key_exists('ContactForm_after', $conf))
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
  
  /**
   * Migration to 2.5
   */
  private function migrate_25($new_conf)
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
      $new_conf['comment'],
      $new_conf['cf_redirect_delay'],
      $new_conf['cf_separator'],
      $new_conf['cf_separator_length'],
      $new_conf['cf_admin_mails']
      );

    // save config
    conf_update_param('ContactForm', $new_conf, true);
    conf_update_param('ContactForm_before', stripslashes(@$conf['persoformtop']), true);
    conf_update_param('ContactForm_after', stripslashes(@$conf['persoformbottom']), true);
    conf_delete_param(array('persoformtop','persoformbottom'));
  }
  
  /**
   * Migration to 2.8
   */
  private function migrate_28($new_conf)
  {
    global $conf;
    
    $query = 'SELECT DISTINCT email, name FROM `' . $this->table . '` WHERE active = \'true\';';
    $emails = query2array($query);
    $users = array();
    
    if (!empty($emails))
    {
      $emails_esc = array();
      foreach ($emails as $e)
      {
        $emails_esc[] = pwg_db_real_escape_string($e['email']);
      }
      
      $query = '
SELECT id, ' . $conf['user_fields']['email'] . ' AS email
  FROM `' . USERS_TABLE . '`
  WHERE ' . $conf['user_fields']['email'] . ' IN (\'' . implode('\',\'', $emails_esc) . '\')
;';
      $users = query2array($query, 'email', 'id');
    
      foreach ($emails as &$e)
      {
        if (isset($users[$e['email']]))
        {
          $e = array(
            'user_id' => $users[$e['email']],
            'name' => null,
            'email' => null
            );
        }
        else
        {
          $e = array(
            'user_id' => null,
            'name' => $e['name'],
            'email' => $e['email']
            );
        }
      }
      unset($e);
    }
    
    pwg_query('TRUNCATE TABLE `' . $this->table . '`');
    
    pwg_query('
ALTER TABLE `' . $this->table . '` 
  ADD `user_id` mediumint(8) unsigned DEFAULT 0, 
  CHANGE `name` `name` varchar(128) NULL DEFAULT NULL, 
  CHANGE `email` `email` varchar(128) NULL DEFAULT NULL, 
  DROP INDEX `UNIQUE`, 
  DROP `group_name`,
  DROP `active`
;');
    
    if (!empty($emails))
    {
      mass_inserts(
        $this->table,
        array('user_id','name','email'),
        $emails
        );
    }
    else
    {
      $new_conf['cf_must_initialize'] = true;
      conf_update_param('ContactForm', $new_conf, true);
    }
  }
}
