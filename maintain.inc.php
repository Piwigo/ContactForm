<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

defined('CONTACT_FORM_ID') or define('CONTACT_FORM_ID', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . CONTACT_FORM_ID . '/include/install.inc.php');

function plugin_install() 
{
  contact_form_install();
  define('contact_form_installed', true);
}

function plugin_activate()
{
  if (!defined('contact_form_installed'))
  {
    contact_form_install();
  }
}

function plugin_uninstall() 
{
  global $prefixeTable, $conf;
  
  pwg_query('DELETE FROM `'. CONFIG_TABLE .'` WHERE param LIKE "ContactForm%";');
  pwg_query('DROP TABLE IF EXISTS `'. $prefixeTable .'contact_form`;');
  
  unset($conf['ContactForm'], $conf['ContactForm_before'], $conf['ContactForm_after']);
}

?>