<?php
/*
Plugin Name: Contact Form
Version: auto
Description: Add a "Contact" item in the Menu block to offer a contact form to users
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=304
Author: Piwigo Team
Author URI: http://piwigo.org
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define('CONTACT_FORM_PATH',  PHPWG_PLUGINS_PATH . basename(dirname(__FILE__)) . '/');
define('CONTACT_FORM_ADMIN', get_root_url() . 'admin.php?page=plugin-' . basename(dirname(__FILE__)));


add_event_handler('init', 'contact_form_init');
add_event_handler('loc_end_section_init', 'contact_form_section_init');
add_event_handler('loc_end_index', 'contact_form_page');
add_event_handler('blockmanager_apply', 'contact_form_applymenu', EVENT_HANDLER_PRIORITY_NEUTRAL+10);
if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'contact_form_admin_menu');
}

include(CONTACT_FORM_PATH . 'include/functions.inc.php');


function contact_form_init()
{
  global $conf;
  $conf['ContactForm'] = unserialize($conf['ContactForm']);
  
  load_language('plugin.lang', CONTACT_FORM_PATH);
  
  if ($conf['ContactForm']['cf_must_initialize'])
  {
    contact_form_initialize_emails();
  }
}

?>