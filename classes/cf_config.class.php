<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

/**
 * CF_Config class
 */
class CF_Config {
  static $default_config = array();
  
  protected $config_values;
  protected $db_key = null;
  protected $db_comment = null;
  protected $config_lang;
  
  /* ************************ */
  /* ** Constructor        ** */
  /* ************************ */

  function CF_Config() {
  }
  
  /* ************************ */
  /* ** Accessors          ** */
  /* ************************ */
  
  function get_value($key) {
      if (isset($this->config_values[$key])) {
          return $this->config_values[$key];
      }
      return null;
  }

  function set_value($key, $value) {
      $this->config_values[$key] = $value;
  }

  function get_version() {
    if (isset($this->config_values[CF_CFG_VERSION])) {
      return $this->config_values[CF_CFG_VERSION];
    }
    return CF_VERSION;
  }

  function set_db_key($key) {
    $this->db_key = $key;
  }
  
  function get_config_lang() {
    return $this->config_lang;
  }
  
  function get_lang_value($item, $language=null) {
    if (null == $language) {
      global $user;
      $language = $user['language'];
    }
    $value = $this->config_lang->get_value($language, $item);
    if (empty($value)) {
      cf_switch_to_default_lang();
      $value = l10n($item);
      cf_switch_back_to_user_lang();
    }
    return $value;
  }
  
  /* ************************ */
  /* ** Loading methods    ** */
  /* ************************ */
  
  function load_config() {
    $this->config_lang = null;
    if (null != $this->db_key) {
      $query = '
          SELECT value
          FROM '.CONFIG_TABLE.'
          WHERE param = \''. $this->db_key .'\'
          ;';
      $result = pwg_query($query);
      if($result) {
        $row = mysql_fetch_row($result);
        if(is_string($row[0])) {
          $this->config_values = unserialize($row[0]);
          if (isset($this->config_values['config_lang'])) {
            $this->config_lang = $this->config_values['config_lang'];
            $this->config_values['config_lang'] = null;
          }
        }
      }
    }
//    CF_Log::add_debug($this->config_lang, 'CF_Config::load_config');
    $this->load_default_config();
  }
  
  protected function load_default_config() {
    if (null == $this->config_lang) {
      $this->config_lang = new CF_Config_Lang();
      $this->config_values['config_lang'] = null;
      CF_Log::add_debug($this->config_lang,'CF_Config::load_default_config');
    }
    $this->config_lang->set_default_values();
    $this->config_lang->update_keys();
    foreach (CF_Config::$default_config as $key => $value) {
      if (!isset($this->config_values[$key])) {
        $this->config_values[$key] = $value;
      }
    }
  }
  
  /* ************************ */
  /* ** Saving  methods    ** */
  /* ************************ */

  function save_config() {
    if (null == $this->db_key) {
      return false;
    }
    $this->config_values['config_lang'] = $this->config_lang;
    if (!isset($this->config_values[CF_CFG_COMMENT])) {
      $this->set_value(CF_CFG_COMMENT, CF_CFG_DB_COMMENT);
    }
    $db_comment = sprintf($this->config_values[CF_CFG_COMMENT],
                          $this->db_key,
                          $this->get_version());
    $query = '
        REPLACE INTO '.CONFIG_TABLE.'
        VALUES(
          \''. $this->db_key .'\',
          \''.serialize($this->config_values).'\',
          \''. $db_comment . '\')
        ;';
    $result = pwg_query($query);
    if($result) {
      return true;
    } else {
      return false;
    }
  }
  
  /* ************************ */
  /* ** Maintain  methods  ** */
  /* ************************ */
  
  static function install($plugin_id) {
    $config = new CF_Config();
    $config->set_db_key($plugin_id);
    $config->load_config();
    $default_config = CF_Config::$default_config;
    if (isset($default_config[CF_CFG_VERSION])) {
      // Override version
      $config->set_value(CF_CFG_VERSION, $default_config[CF_CFG_VERSION]);
    }
    if (isset($default_config[CF_CFG_COMMENT])) {
      // Override comment
      $config->set_value(CF_CFG_COMMENT, $default_config[CF_CFG_COMMENT]);
    }
    $result = $config->save_config();
    return $result;
  }

  static function uninstall($plugin_id) {
    $query = '
        DELETE FROM '.CONFIG_TABLE.'
        WHERE param = \'' . $plugin_id . '\'
        ;';
    $result = pwg_query($query);
    if($result) {
      return true;
    } else {
      return false;
    }
  }  
}
?>