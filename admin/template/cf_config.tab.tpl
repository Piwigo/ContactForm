{* $Id: cf_config.tab.tpl,v 1.3 2009/08/18 14:40:36 Criss Exp $ *}
<div class="titrePage">
    <h2>{$CF.TITLE} [{$CF.VERSION}]<br>{'cf_config'|@translate}</h2>
</div>
<h3>{'cf_config_desc'|@translate}</h3>
<form method="post" action="{$CF.F_ACTION}" id="update" enctype="multipart/form-data">
<fieldset>
  <legend>{'cf_label_config'|@translate}</legend>
  <ul>
    <li>
      <label>
        <input type="checkbox" name="cf_menu_link" value="1" {$CF_CONFIG.MENU_LINK} />
        {'cf_menu_link'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="checkbox" name="cf_guest_allowed" value="1" {$CF_CONFIG.GUEST} />
        {'cf_guest_allowed'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="checkbox" name="cf_mandatory_name" value="1" {$CF_CONFIG.NEED_NAME} />
        {'cf_mandatory_name'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="checkbox" name="cf_mandatory_mail" value="1" {$CF_CONFIG.NEED_MAIL} />
        {'cf_mandatory_mail'|@translate}
      </label>
    </li>
  </ul>
</fieldset>
<fieldset>
  <legend>{'cf_label_link'|@translate}</legend>
  <ul>
    <li>
      <label>
        <input type="checkbox" name="cf_define_link" value="1" {$CF_CONFIG.DEFINE_LINK} />
        {'cf_define_link'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="text" name="cf_link" value="{$CF_CONFIG.LINK}" />
        {'cf_link'|@translate}
      </label>
    </li>
  </ul>
</fieldset>
<fieldset>
  <legend>{'cf_label_mail'|@translate}</legend>
  <ul>
    <li>
      <label>
        <input type="text" name="cf_mail_prefix" value="{$CF_CONFIG.PREFIX}" maxlength="50" size="50"/>
        {'cf_mail_prefix'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="text" name="cf_separator" value="{$CF_CONFIG.SEPARATOR}" maxlength="2" size="2"/>
        {'cf_separator'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="text" name="cf_separator_length" value="{$CF_CONFIG.SEPARATOR_LENGTH}" maxlength="2" size="2"/>
        {'cf_separator_length'|@translate}
      </label>
    </li>
    <li>
      <label>
        <input type="text" name="cf_redirect_delay" value="{$CF_CONFIG.REDIRECT_DELAY}" maxlength="2" size="2"/>
        {'cf_redirect_delay'|@translate}
      </label>
    </li>
  </ul>
</fieldset>

{* ----------- CONFIGURATION ----------- *}
<p><input class="submit" type="submit" value="{'cf_validate'|@translate}" name="submit"></p>
</form>