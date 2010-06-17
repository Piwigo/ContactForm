<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

class CF_Config_Lang {
  static $default_keys = array();
  static protected $available_languages = null;
  protected $defined_languages = array();
  protected $added_languages = array();
  protected $keys = array();
  
  static private function update_available_languages() {
    CF_Config_Lang::$available_languages = array();
    foreach(get_dirs(CF_LANGUAGE) as $language) {
      array_push(CF_Config_Lang::$available_languages, $language);
    }
    return CF_Config_Lang::$available_languages;
  }
  static function get_available_languages() {
    if (empty(CF_Config_Lang::$available_languages)) {
      CF_Config_Lang::update_available_languages();
    }
    return CF_Config_Lang::$available_languages;
  }
  /* ************************ */
  /* ** Constructor        ** */
  /* ************************ */

  function __construct() {
    $this->init();
  }

  /* ************************ */
  /* ** Accessors          ** */
  /* ************************ */

  function update_keys() {
    $this->keys = array();
    foreach($this->get_merged_values() as $language => $keys) {
      foreach($keys as $key => $value) {
        $this->add_key($key);
      }
    }
  }
  
  function set_default_values() {
    foreach(CF_Config_Lang::$default_keys as $default_key => $default_values) {
      foreach($default_values as $language => $value) {
        if (empty($this->defined_languages[$language][$default_key])) {
          $this->defined_languages[$language][$default_key] = $value;
        }
      }
      $this->add_key($default_key);
    }
  }
  
  function get_keys() {
    return $this->keys;
  }
  
  function add_key($key) {
    if (in_array($key, $this->keys)) {
      // already exists
      return;
    }
    $this->keys[]=$key;
    // Add key to all languages
    foreach($this->automatic_values as $language => $keys) {
      if (!isset($this->defined_languages[$language][$key])) {
        $this->defined_languages[$language][$key] = '';
      }
    }
    foreach($this->added_languages as $language => $keys) {
      if (!isset($this->added_languages[$language][$key])) {
        $this->added_languages[$language][$key] = '';
      }
    }
  }
  
  function get_value($language=null, $key=null, $return_default=true) {
    $values = $this->get_merged_values();
    if (null == $language) {
      return $values;
    }
    if (!isset($values[$language])) {
      return null;
    }
    if (null == $key) {
      return $values[$language];
    }
    if (!isset($values[$language][$key]) or empty($values[$language][$key])) {
      if ($return_default) {
        return $values[CF_LANG_DEFAULT][$key];
      }
      return null;
    }
    return $values[$language][$key];
  }
  
  function set_value($language, $key, $value) {
    if (isset($this->defined_languages[$language])) {
      $this->defined_languages[$language][$key] = $value;
    } else {
      if (!isset($this->added_languages[$language])) {
        $this->added_languages[$language] = array();
      }
      $this->added_languages[$language][$key] = $value;
    }
    $this->add_key($key);
  }
  
  function get_extended_values($language=null) {
    $values = $this->get_merged_values();
    $return_values = array();
    foreach($values as $language => $keys) {
      foreach($keys as $key => $value) {
        if (!isset($return_values[$key])) {
          $return_values[$key] = '';
        }
        if (!empty($value)) {
          if (strcmp($language, CF_LANG_DEFAULT) != 0) {
            $language = substr($language, 0, 2);
          }
          $return_values[$key] .= '[lang=' . $language . ']';
          $return_values[$key] .= $value;
          $return_values[$key] .= '[/lang]';
        }
      }
    }
    return $return_values;
  }
  
  function mass_update($new_values = array()) {
    foreach($new_values as $key => $values) {
      if (is_array($values)) {
        foreach($values as $language => $content) {
          $this->set_value($language, $key, $content);
        }
      }
    }
  }
  
  /* ************************ */
  /* ** Private functions  ** */
  /* ************************ */

  protected function get_merged_values() {
    return array_merge_recursive($this->defined_languages, $this->added_languages);
  }
  
  protected function init() {
    $this->automatic_values = array();
    foreach(CF_Config_Lang::get_available_languages() as $language) {
      $this->defined_languages[$language] = array();
    }
  }
}
?>