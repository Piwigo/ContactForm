{* $Id: cf_language.tab.tpl,v 1.1 2009/08/17 07:24:12 Criss Exp $ *}
{literal}
<script type="text/javascript">
function cf_update_display() {
  var select_item = document.getElementById('cf_select');
  for (var i=0 ; i<select_item.length ; i++) {
    var div_item = document.getElementById('cf_div_' + select_item[i].value);
    var visible = select_item[i].selected;
    cf_set_visible(div_item, visible);
  }
}
</script>
{/literal}<div class="titrePage">
    <h2>{$CF.TITLE} [{$CF.VERSION}]<br>{'cf_language'|@translate}</h2>
</div>
<h3>{'cf_language_desc'|@translate}</h3>

<form method="post" action="{$CF.F_ACTION}" id="update" enctype="multipart/form-data">
<table>
  <tr>
    <td style="vertical-align: top;">
      <div class="cf-label">{'cf_select_item'|@translate}</div>
      <select size="1" name="cf_select" id="cf_select" onclick="cf_update_display();">
      {html_options options=$CF_CONFIG_KEYS selected=$CF_CONFIG_KEYS_SELECTED}
      </select>
    </td>
    <td>&nbsp;</td>
    <td>
      {foreach from=$CF_CONFIG_VALUES item=config_value key=config_key name=config_keys}
      <div id="cf_div_{$config_key}" class="cf-lang">
        <label class="cf-lang">{$CF_CONFIG_KEYS.$config_key}</label>
        <table>
        {foreach from=$config_value item=language_values key=language_key}
          <tr>
              <td align="right">{$language_values.NAME}</td>
              <td>&nbsp;</td>
              <td>
                <input type="text" name="cf_item[{$config_key}][{$language_key}]" value="{$language_values.VALUE}"/>
              </td>
          </tr>
        {/foreach}
        </table>
      </div>
      {/foreach}
    </td>
  </tr>
</table>
{* ----------- LANGUAGES ----------- *}
<p><input class="submit" type="submit" value="{'cf_validate'|@translate}" name="submit"></p>
</form>
{literal}
<script type="text/javascript">
cf_update_display();
</script>
{/literal}