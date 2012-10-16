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

global $prefixeTable;

defined('CONTACT_FORM_ID') or define('CONTACT_FORM_ID', basename(dirname(__FILE__)));
define('CONTACT_FORM_PATH',    PHPWG_PLUGINS_PATH . CONTACT_FORM_ID . '/');
define('CONTACT_FORM_ADMIN',   get_root_url() . 'admin.php?page=plugin-' . CONTACT_FORM_ID);
define('CONTACT_FORM_PUBLIC',  get_absolute_root_url() . make_index_url(array('section' => 'contact')) . '/');
define('CONTACT_FORM_TABLE',   $prefixeTable .'contact_form');
define('CONTACT_FORM_VERSION', 'auto');


add_event_handler('init', 'contact_form_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'contact_form_admin_menu');
}
else
{
  add_event_handler('loc_end_section_init', 'contact_form_section_init');
  add_event_handler('loc_end_index', 'contact_form_page');
  add_event_handler('blockmanager_apply', 'contact_form_applymenu', EVENT_HANDLER_PRIORITY_NEUTRAL+10);
}

include(CONTACT_FORM_PATH . 'include/functions.inc.php');


/**
 * update & unserialize conf & load language & init emails
 */
function contact_form_init()
{
  global $conf, $template,  $pwg_loaded_plugins;
  
  if (
    CONTACT_FORM_VERSION == 'auto' or
    $pwg_loaded_plugins[CONTACT_FORM_ID]['version'] == 'auto' or
    version_compare($pwg_loaded_plugins[CONTACT_FORM_ID]['version'], CONTACT_FORM_VERSION, '<')
  )
  {
    include_once(CONTACT_FORM_PATH . 'include/install.inc.php');
    contact_form_install();
    
    if ( $pwg_loaded_plugins[CONTACT_FORM_ID]['version'] != 'auto' and CONTACT_FORM_VERSION != 'auto' )
    {
      $query = '
UPDATE '. PLUGINS_TABLE .'
SET version = "'. CONTACT_FORM_VERSION .'"
WHERE id = "'. CONTACT_FORM_ID .'"';
      pwg_query($query);
      
      $pwg_loaded_plugins[CONTACT_FORM_ID]['version'] = CONTACT_FORM_VERSION;
      
      if (defined('IN_ADMIN'))
      {
        $_SESSION['page_infos'][] = 'ContactForm updated to version '. CONTACT_FORM_VERSION;
      }
    }
  }
  
  $conf['ContactForm'] = unserialize($conf['ContactForm']);
  load_language('plugin.lang', CONTACT_FORM_PATH);
  
  if ($conf['ContactForm']['cf_must_initialize'])
  {
    contact_form_initialize_emails();
  }
  
  $template->set_prefilter('tail', 'contact_form_footer_link');
}

/**
 * admin plugins menu link 
 */
function contact_form_admin_menu($menu) 
{
  array_push($menu, array(
    'URL' => CONTACT_FORM_ADMIN,
    'NAME' => 'Contact Form',
  ));
  return $menu;
}

?>