<?php
/* $Id: cf_language.tab.php,v 1.1 2009/08/17 07:24:11 Criss Exp $ */
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
check_status(ACCESS_ADMINISTRATOR);

$all_languages = get_languages();
$cf_languages = $cf_config->get_config_lang();
$cf_item_selected='';
if (isset($_POST['submit'])) {
  global $page;
  
  if (isset($_POST['cf_item']) and is_array($_POST['cf_item'])) {
    CF_Debug::add_debug($_POST['cf_item'], 'POST');
    foreach($_POST['cf_item'] as $key => $values) {
      if (is_array($values)) {
        CF_Debug::add_debug($values, 'POST');
        foreach($values as $language => $content) {
          $cf_languages->set_value($language, $key, $content);
        }
      }
    }
  }
  CF_Debug::add_debug( $cf_languages, 'POST');
  // Save config
  $cf_config->save_config();
  $saved = $cf_config->save_config();
  if ($saved) {
      array_push($page['infos'], l10n('cf_config_saved'));
  } else {
      array_push($page['errors'], l10n('cf_config_saved_with_errors'));
  }
  
  if (isset($_POST['cf_select'])) {
    $cf_item_selected = $_POST['cf_select']; 
  }
}

$config_keys=array();
$config_values=array();
foreach($cf_languages->get_keys() as $key) {
  $current = array();
  foreach($all_languages as $lang_key => $lang_name) {
    $current[$lang_key] = array(
        'LANG'  => $lang_key,
        'NAME'  => $lang_name,
        'VALUE' => $cf_languages->get_value($lang_key, $key),
      );
  }
  $config_keys[$key] = l10n($key . '_label');
  $config_values[$key] = $current;
}

$template->assign('CF_CONFIG_KEYS_SELECTED', $cf_item_selected);
$template->assign('CF_CONFIG_KEYS', $config_keys);
$template->assign('CF_CONFIG_VALUES', $config_values);

?>