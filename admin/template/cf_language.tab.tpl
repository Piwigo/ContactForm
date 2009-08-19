{* $Id: cf_language.tab.tpl,v 1.4 2009/08/19 14:51:59 Criss Exp $ *}
{known_script id="jquery.ui" src=$ROOT_URL|@cat:"template-common/lib/ui/ui.core.packed.js"}
{known_script id="jquery.ui.tabs" src=$ROOT_URL|@cat:"template-common/lib/ui/ui.tabs.packed.js"}
<script type="text/javascript">
function set_active(key) {ldelim}
  var element = document.getElementById('cf_selected');
  element.value = key;
}
jQuery().ready(
  function(){ldelim}
    jQuery('#cf-keys').accordion({ldelim}
      header: '.cf-label',
      event: 'click',
      active: {$CF_CONFIG_KEYS_SELECTED}
    });
  }
);
</script>
<div class="titrePage">
    <h2>{$CF.TITLE} [{$CF.VERSION}]<br>{'cf_language'|@translate}</h2>
</div>
<h3 style="width: 100%;">{'cf_language_desc'|@translate}</h3>

<form method="post" action="{$CF.F_ACTION}" id="update" enctype="multipart/form-data">
<div id="cf-keys">
<input type="hidden" id ="cf_selected" name="cf_selected" value="{$CF_CONFIG_KEYS_SELECTED}">
{foreach from=$CF_CONFIG_VALUES item=config_value key=config_key name=config_values}
  <div class="cf-label" onclick="set_active({$smarty.foreach.config_values.index});">
    {$config_value.KEY}
  </div>
  <div>
    <table class="checking">
    {foreach from=$config_value.VALUE item=language_values key=language_key}
      <tr>
        <td class="cf-lang">{$language_values.NAME}</td>
        <td class="cf-input"><input type="text" name="cf_item[{$config_key}][{$language_key}]" value="{$language_values.VALUE}" size="30"/></td>
      </tr>
    {/foreach}
    </table>
  </div>
{/foreach}
</div>
{* ----------- LANGUAGES ----------- *}
<p><input class="submit" type="submit" value="{'cf_validate'|@translate}" name="submit"></p>
</form>
{literal}
<script type="text/javascript">
$(document).ready(function() {
    $(".infos").fadeOut(800).fadeIn(1200).fadeOut(400).fadeIn(800).fadeOut(400);
    $(".errors").fadeOut(200).fadeIn(200).fadeOut(300).fadeIn(300).fadeOut(400).fadeIn(400); 
  });
</script>
{/literal}