<?php
/* $Id: cf_language.tab.php,v 1.5 2009/08/19 14:51:59 Criss Exp $ */
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
check_status(ACCESS_ADMINISTRATOR);
global $user;
CF_Log::add_debug($user, 'USER');
$all_languages = get_languages();
$cf_languages = $cf_config->get_config_lang();
$cf_item_selected='';
if (isset($_POST['submit'])) {
  
  if (isset($_POST['cf_item']) and is_array($_POST['cf_item'])) {
    $cf_languages->mass_update($_POST['cf_item']);
  }

  // Save config
  $cf_config->save_config();
  $saved = $cf_config->save_config();
  if ($saved) {
    CF_Log::add_message(l10n('cf_config_saved'));
  } else {
    CF_Log::add_error(l10n('cf_config_saved_with_errors'));
  }
  
  if (isset($_POST['cf_selected'])) {
    $cf_item_selected = trim(stripslashes($_POST['cf_selected'])); 
  }
}

$config_values=array();

foreach($cf_languages->get_keys() as $key) {
  $current = array();
  $current[CF_LANG_DEFAULT] = array(
        'LANG'  => CF_LANG_DEFAULT,
        'NAME'  => l10n('cf_default_lang'),
        'VALUE' => $cf_languages->get_value(CF_LANG_DEFAULT, $key, false),
      );
  foreach($all_languages as $lang_key => $lang_name) {
    $current[$lang_key] = array(
        'LANG'  => $lang_key,
        'NAME'  => $lang_name,
        'VALUE' => $cf_languages->get_value($lang_key, $key, false),
      );
  }
  $config_values[$key] = array(
      'KEY' => l10n($key . '_label'),
      'VALUE' => $current,
    );
}
if ('' == $cf_item_selected) {
  $cf_item_selected = 0;
}
$template->assign('CF_CONFIG_KEYS_SELECTED', $cf_item_selected);
$template->assign('CF_CONFIG_VALUES', $config_values);
?>