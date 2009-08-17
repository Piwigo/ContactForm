<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

class CF_Debug {
  static $debug=array();
  
  static function show_debug() {
    if (!defined('CF_DEBUG_ACTIVE') or !CF_DEBUG_ACTIVE) {
      return;
    }
    echo '<pre>';
    foreach(CF_Debug::$debug as $label => $values) {
      echo '{== BEGIN ' . $label . ' ==}<br>';
      foreach($values as $debug_value) {
        echo $debug_value . '<br>';
      }
      echo '{== END   ' . $label . ' ==}<br>';
    }
    echo '</pre><br>';
  }
  static function add_debug($variable, $label=null) {
    $value = print_r($variable, true);
    if (null == $label) {
      $label = 'CF_Debug';
    }
    if (!isset(CF_Debug::$debug[$label])) {
      CF_Debug::$debug[$label] = array();
    }
    array_push(CF_Debug::$debug[$label], $value);
  }
  
}
?>