{known_script id="jquery.ui" src=$ROOT_URL|@cat:"themes/default/js/ui/packed/ui.core.packed.js"}
<div class="cf-button">
<input type="button" value="{'cf_hide'|@translate}" onclick="hide('cf_messages');">
</div>
{literal}
<script type="text/javascript">
function hide(id) {
  var element = document.getElementById(id);
  if (null != element) {
    element.style.visibility = 'hidden';
    element.style.display = 'none';
  }
}
</script>
{/literal}