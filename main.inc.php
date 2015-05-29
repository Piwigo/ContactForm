<?php
/*
Plugin Name: Contact Form
Version: auto
Description: Add a "Contact" item in the Menu block to offer a contact form to users
Plugin URI: auto
Author: Piwigo Team
Author URI: http://piwigo.org
*/

/*
 * $conf['contact_form_show_ip'] = true;
 */

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

if (basename(dirname(__FILE__)) != 'ContactForm')
{
  add_event_handler('init', 'contact_form_error');
  function contact_form_error()
  {
    global $page;
    $page['errors'][] = 'Contact Form folder name is incorrect, uninstall the plugin and rename it to "ContactForm"';
  }
  return;
}

global $conf, $prefixeTable;

define('CONTACT_FORM_PATH',   PHPWG_PLUGINS_PATH . 'ContactForm/');
define('CONTACT_FORM_ADMIN',  get_root_url() . 'admin.php?page=plugin-ContactForm');
define('CONTACT_FORM_PUBLIC', get_absolute_root_url() . make_index_url(array('section' => 'contact')) . '/');
define('CONTACT_FORM_TABLE',  $prefixeTable .'contact_form');

$conf['ContactForm'] = safe_unserialize($conf['ContactForm']);

include(CONTACT_FORM_PATH . 'include/functions.inc.php');


add_event_handler('init', 'contact_form_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'contact_form_admin_menu');
  add_event_handler('delete_user', 'contact_form_delete_user');
}
else
{
  add_event_handler('loc_end_section_init', 'contact_form_section_init');
  add_event_handler('loc_end_index', 'contact_form_page');
}

if ($conf['ContactForm']['cf_menu_link'])
{
  add_event_handler('blockmanager_apply', 'contact_form_applymenu', EVENT_HANDLER_PRIORITY_NEUTRAL+10);
}

add_event_handler('ws_add_methods', 'contact_form_ws_add_methods');

add_event_handler('before_parse_mail_template', 'contact_form_mail_template');


/**
 * update & unserialize conf & load language & init emails
 */
function contact_form_init()
{
  global $conf, $template;

  load_language('plugin.lang', CONTACT_FORM_PATH);
  load_language('lang', PHPWG_ROOT_PATH.PWG_LOCAL_DIR, array('no_fallback'=>true, 'local'=>true));

  if ($conf['ContactForm']['cf_must_initialize'])
  {
    contact_form_initialize_emails();
  }

  $conf['ContactForm_ready'] = count(get_contact_emails());

  if ($conf['ContactForm_ready'] && (!is_a_guest() || $conf['ContactForm']['cf_allow_guest']))
  {
    $template->assign(array(
      'CONTACT_MAIL' => true,
      'CONTACT_FORM_PUBLIC' => CONTACT_FORM_PUBLIC,
      ));
    $template->set_prefilter('tail', 'contact_form_footer_link');
  }
}

/**
 * admin plugins menu link
 */
function contact_form_admin_menu($menu)
{
  $menu[] = array(
    'URL' => CONTACT_FORM_ADMIN,
    'NAME' => 'Contact Form',
  );
  return $menu;
}
