<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

/* ************************** */
/* ** Constants            ** */
/* ************************** */

// Version
define('CF_VERSION',            '1.1.9');
define('CF_TITLE',              'cf_plugin_name');

// Directories
define('CF_INCLUDE_DIR',        'include/');
define('CF_CLASSES_DIR',        'classes/');
define('CF_IMAGES_DIR',         'images/');
define('CF_TEMPLATE_DIR',       'themes/');
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
define('CF_CHANGELOG',          'CHANGELOG');
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
define('CF_LANG_DEFAULT',       'default');
// Config keys
if (isset($plugin)) {
  define('CF_CFG_DB_KEY',       $plugin['id']);
}
define('CF_CFG_DB_FACTORY',     'Factory settings for plugin %s [%s]');
define('CF_CFG_DB_COMMENT',     'Configuration of plugin %s [%s]');
define('CF_CFG_COMMENT',        'comment');
define('CF_CFG_VERSION',        'version');

define('CF_CFG_MENU_LINK',      'cf_menu_link');
define('CF_CFG_SUBJECT_PREFIX', 'cf_subject_prefix');
define('CF_CFG_SEPARATOR_LEN',  'cf_separator_length');
define('CF_CFG_SEPARATOR',      'cf_separator');
define('CF_CFG_ALLOW_GUEST',    'cf_allow_guest');
define('CF_CFG_MAIL_MANDATORY', 'cf_mandatory_mail');
define('CF_CFG_NAME_MANDATORY', 'cf_mandatory_name');
define('CF_CFG_REDIRECT_DELAY', 'cf_redirect_delay');
define('CF_CFG_ADMIN_MAILS',    'cf_admin_mails');

/* ************************** */
/* ** Includes             ** */
/* ************************** */

// Include plugin functions
@include_once(CF_INCLUDE.'cf_functions.inc.php');

// Load class files
cf_require_class("CF_Log");
cf_require_class("CF_Config_Lang");
cf_require_class("CF_Config");
cf_require_class("CF_Plugin");

/* ************************** */
/* ** Variable definitions ** */
/* ************************** */
global $conf;
$cf_config_default = array();
$cf_config_default[CF_CFG_MENU_LINK] = true;
$cf_config_default[CF_CFG_SUBJECT_PREFIX] = CF_DEFAULT_PREFIX;
$cf_config_default[CF_CFG_SEPARATOR_LEN] = CF_SEPARATOR_LENGTH;
$cf_config_default[CF_CFG_SEPARATOR] = CF_SEPARATOR;
$cf_config_default[CF_CFG_ALLOW_GUEST] = true;
$cf_config_default[CF_CFG_MAIL_MANDATORY] = true;
$cf_config_default[CF_CFG_NAME_MANDATORY] = true;
$cf_config_default[CF_CFG_REDIRECT_DELAY] = CF_REDIRECT_DELAY;
$cf_config_default[CF_CFG_ADMIN_MAILS] = cf_get_admins_contacts();
$cf_config_default[CF_CFG_ADMIN_MAILS] = array();
CF_Config::$default_config = $cf_config_default;

$cf_config_lang_keys = array();
$cf_config_lang_keys['contact_form_title'] = array(
    CF_LANG_DEFAULT => l10n('contact_form_title'),
	'ar_SA' => 'نموذج الاتصال',
	'cs_CZ' => 'Kontaktní formulář',
	'de_DE' => 'Kontaktformular',
    'en_UK' => 'Contact form',
	'es_ES' => 'Formulario de contacto',
    'fr_FR' => 'Formulaire de contact',
	'hu_HU' => 'Kapcsolati urlap',
    'it_IT' => 'Formulario di contatto',
	'lv_LV' => 'Kontaktforma',
	'nl_NL' => 'Contact formulier',
	'no_NO' => 'Kontakt skjema',
	'pl_PL' => 'Formularz kontaktu',
	'ru_RU' => 'Контактная информация',
	'sk_SK' => 'Kontaktný formulár',
	'sv_SE' => 'Kontakt formulär',
	'tr_TR' => 'İletişim formu'
);
$cf_config_lang_keys['contact_form'] = array(
    CF_LANG_DEFAULT => l10n('contact_form'),
	'ar_SA' => 'اتصل بنا',
	'cs_CZ' => 'Kontakt',
	'de_DE' => 'Kontaktformular',
    'en_UK' => 'Contact',
	'es_ES' => 'Contactar',
    'fr_FR' => 'Formulaire de contact',
	'hu_HU' => 'Kapcsolat',
    'it_IT' => 'Contattare',
	'lv_LV' => 'Kontaktēt',
	'nl_NL' => 'Contact',
	'no_NO' => 'Kontakt',
	'pl_PL' => 'Kontakt',
	'ru_RU' => 'Контакты',
	'sk_SK' => 'Kontakt',
	'sv_SE' => 'Kontakt',
	'tr_TR' => 'İletişim'
); 
$cf_config_lang_keys['contact_form_link'] = array(
    CF_LANG_DEFAULT => l10n('contact_form_link'),
	'ar_SA' => 'اتصل بمدير الموقع',
	'cs_CZ' => 'Kontakt správce webu',
	'de_DE' => 'Den Webmaster kontaktieren',
    'en_UK' => 'Contact webmaster',
	'es_ES' => 'Contactar webmestre',
    'fr_FR' => 'Contacter le webmestre',
	'hu_HU' => 'Webmester kapcsolat',
    'it_IT' => 'Contattare il webmaster',
	'lv_LV' => 'Kontaktēt ar webmāsteru',
	'nl_NL' => 'Contact webmaster',
	'no_NO' => 'Kontakt webmaster',
	'pl_PL' => 'Kontakt do webmastera',
	'ru_RU' => 'Contact webmaster',
	'sk_SK' => 'Kontaktovanie webmastera',
	'sv_SE' => 'Kontakta webmaster',
	'tr_TR' => 'İletişim- Site yöneticisi'
);
CF_Config_Lang::$default_keys = $cf_config_lang_keys;
?>