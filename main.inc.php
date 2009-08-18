<?php
/*
 Plugin Name: Contact Form
 Version: 1.0.3
 Description: Add a "Contact" item in the Menu block to offer a contact form to users
 Plugin URI: http://piwigo.org/ext/extension_view.php?eid=304
 Author: Criss
 Author URI: http://piwigo.org/
*/

/** History **

  2009-08-18 1.0.3
                    Add configuration option to define menu link or not

  2009-08-18 1.0.2
                    Add configuration option to define template variable or not

  2009-08-17 1.0.1
                    Add default value to language translation

  2009-08-17 1.0.0
                    Put under SVN control

  2009-08-17 0.1.f
                    Add obsolete list

  2009-08-17 0.1.e
                    Add language configuration for items texts
                    Add template variable

  2009-08-14 0.1.d
                    Add a redirection page when successfully sent message

  2009-08-13 0.1.c
                    Fix regexp bug in mail format check in javascript

  2009-08-13 0.1.b
                    Add admin management

  2009-08-13 0.1.a
                    Plugin creation

*/
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define('CF_PATH',     PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');
define('CF_ROOT',     dirname(__FILE__).'/');
include_once(CF_PATH . 'include/cf_common.inc.php');

$cf_plugin = new CF_Plugin($plugin['id']);
add_event_handler('loc_begin_page_tail',             
                  array(&$cf_plugin, 'loc_begin_page_header'));
add_event_handler('blockmanager_apply',             
                  array(&$cf_plugin, 'blockmanager_apply'));
add_event_handler('loc_end_index',             
                  array(&$cf_plugin, 'loc_end_index'));
add_event_handler('loc_end_page_tail',
                  array(&$cf_plugin, 'loc_end_page_tail'));
if(defined('IN_ADMIN')) {
  add_event_handler('get_admin_plugin_menu_links',
                    array(&$cf_plugin, 'get_admin_plugin_menu_links'));
}
set_plugin_data($plugin['id'], $cf_plugin);
?>