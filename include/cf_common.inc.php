<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

/* ************************** */
/* ** Constants            ** */
/* ************************** */

// Version
define('CF_VERSION',            '1.0.1');
define('CF_TITLE',              'Contact Form');

// Directories
define('CF_INCLUDE_DIR',        'include/');
define('CF_CLASSES_DIR',        'classes/');
define('CF_IMAGES_DIR',         'images/');
define('CF_TEMPLATE_DIR',       'template/');
define('CF_ADMIN_DIR',          'admin/');
define('CF_LANGUAGE_DIR',       'language/');
// Path
define('CF_CLASSES',            CF_PATH.CF_CLASSES_DIR);
define('CF_INCLUDE',            CF_PATH.CF_INCLUDE_DIR);
define('CF_TEMPLATE',           CF_PATH.CF_TEMPLATE_DIR);
define('CF_LANGUAGE',           CF_PATH.CF_LANGUAGE_DIR);
define('CF_ADMIN',              CF_PATH.CF_ADMIN_DIR);
define('CF_AMDIN_TPL',          CF_PATH.CF_ADMIN_DIR.CF_TEMPLATE_DIR);
// Files
define('CF_OBSOLETE',           'obsolete.list');
// Constants
define('CF_DEBUG_ACTIVE',       false);
define('CF_MENUBAR_KEY',        'contact_form');
define('CF_URL_PARAMETER',      'contact');
define('CF_SEPARATOR_PATTERN',  '||SEPARATOR HERE||');
define('CF_CHECKED',            'checked="checked"');
define('CF_SEPARATOR',          '=');
define('CF_SEPARATOR_LENGTH',   20);
define('CF_DEFAULT_PREFIX',     'Piwigo ContactForm');
define('CF_REDIRECT_DELAY',     5);
define('CF_DEFAULT_LINKNAME',   'ContactFormLink');
define('CF_LANG_DEFAULT',       'default');
// Config keys
if (isset($plugin)) {
  define('CF_CFG_DB_KEY',       $plugin['id']);
}
define('CF_CFG_DB_FACTORY',     'Factory settings for plugin %s [%s]');
define('CF_CFG_DB_COMMENT',     'Configuration of plugin %s [%s]');
define('CF_CFG_COMMENT',        'comment');
define('CF_CFG_VERSION',        'version');

define('CF_CFG_SUBJECT_PREFIX', 'cf_subject_prefix');
define('CF_CFG_SEPARATOR_LEN',  'cf_separator_length');
define('CF_CFG_SEPARATOR',      'cf_separator');
define('CF_CFG_ALLOW_GUEST',    'cf_allow_guest');
define('CF_CFG_MAIL_MANDATORY', 'cf_mandatory_mail');
define('CF_CFG_NAME_MANDATORY', 'cf_mandatory_name');
define('CF_CFG_REDIRECT_DELAY', 'cf_redirect_delay');
define('CF_CFG_CONTACT_LINK',   'cf_link');

/* ************************** */
/* ** Includes             ** */
/* ************************** */

// Include plugin functions
@include_once(CF_INCLUDE.'cf_functions.inc.php');

// Load class files
cf_require_class("CF_Debug");
cf_require_class("CF_Config_Lang");
cf_require_class("CF_Config");
cf_require_class("CF_Plugin");

/* ************************** */
/* ** Variable definitions ** */
/* ************************** */
global $conf;
$cf_config_default = array();
$cf_config_default[CF_CFG_SUBJECT_PREFIX] = CF_DEFAULT_PREFIX;
$cf_config_default[CF_CFG_SEPARATOR_LEN] = CF_SEPARATOR_LENGTH;
$cf_config_default[CF_CFG_SEPARATOR] = CF_SEPARATOR;
$cf_config_default[CF_CFG_ALLOW_GUEST] = true;
$cf_config_default[CF_CFG_MAIL_MANDATORY] = true;
$cf_config_default[CF_CFG_NAME_MANDATORY] = true;
$cf_config_default[CF_CFG_REDIRECT_DELAY] = CF_REDIRECT_DELAY;
$cf_config_default[CF_CFG_CONTACT_LINK] = CF_DEFAULT_LINKNAME;
CF_Config::$default_config = $cf_config_default;

$cf_config_lang_keys = array();
$cf_config_lang_keys['contact_form_title'] = array(
    CF_LANG_DEFAULT => l10n('contact_form_title'),
    'fr_FR' => 'Formulaire de contact',
    'en_UK' => 'Contact form',
    'it_IT' => '',
);
$cf_config_lang_keys['contact_form'] = array(
    CF_LANG_DEFAULT => l10n('contact_form'),
    'fr_FR' => 'Contacter',
    'en_UK' => 'Contact',
    'it_IT' => '',
);
$cf_config_lang_keys['contact_form_link'] = array(
    CF_LANG_DEFAULT => l10n('contact_form_link'),
    'fr_FR' => 'Contacter le webmestre',
    'en_UK' => 'Contact webmaster',
    'it_IT' => 'Contattare il webmaster',
);
CF_Config_Lang::$default_keys = $cf_config_lang_keys;
?>