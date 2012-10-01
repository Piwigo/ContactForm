{combine_css path=$CONTACT_FORM_PATH|@cat:"admin/template/style.css"}


<div class="titrePage">
	<h2>Contact Form</h2>
</div>


<form method="post" action="{$CONTACT_FORM_ADMIN}-emails" class="properties">
  <table class="table2" id="emails">
    <tr class="throw">
      <th>{'Name'|@translate}</th>
      <th>{'Email address'|@translate}</th>
      <th>{'Category'|@translate}</th>
      <th>{'Active'|@translate}</th>
      <th>{'Delete'|@translate}</th>
    </tr>
  {counter start=0 assign=i}
  {foreach from=$EMAILS item=entry}
    <tr class="{if $i is odd}row1{else}row2{/if}">
      <td>
        <input type="text" name="emails[{$i}][name]" value="{$entry.name|escape:html}" size="20">
      </td>
      <td>
        <input type="text" name="emails[{$i}][email]" value="{$entry.email}" size="30">
      </td>
      <td>
        <select name="emails[{$i}][group_name]" class="groups">
          <option value="-1">------------</option>
          {html_options values=$GROUPS output=$GROUPS selected=$entry.group_name}
        </select>
      </td>
      <td style="text-align:center;">
        <input type="checkbox" name="emails[{$i}][active]" value="1" {if $entry.active}checked="checked"{/if}>
      </td>
      <td style="text-align:center;">
        <input type="checkbox" name="emails[{$i}][delete]" value="1" class="delete">
      </td>
    </tr>
    {counter}
  {/foreach}
    <tr class="{if $i is odd}row1{else}row2{/if}" id="addEntryContainer">
      <td colspan="2" style="text-align:center;">
        <a id="addEntry">{'+ Add an email'|@translate}</a>
      </td>
      <td>
        <a id="addGroup">{'+ Add a category'|@translate}</a>
      </td>
      <td colspan="2"  style="text-align:center;">
        <input type="submit" name="save_emails" value="{'Submit'|@translate}" class="submit">
      </td>
    </tr>
  </table>
  {footer_script}var entry = {$i};{/footer_script}
</form>

<div class="infos tip">
<b>{'Tip'|@translate}:</b>
{'Each category is displayed as a distinct "service" on the contact form (example: "Technical", "Commercial", "General question"). Using categories is not mandatory.'|@translate}
</div>



{footer_script}
var group_options = new Array;
{foreach from=$GROUPS item=entry}
group_options[group_options.length] = '<option value="{$entry|escape:javascript}">{$entry|escape:javascript}</option>';
{/foreach}

{literal}
var doBlink = function(obj,start,finish) { jQuery(obj).fadeOut(300).fadeIn(300); if(start!=finish) { start=start+1; doBlink(obj,start,finish); } };
jQuery.fn.blink = function(start,finish) { return this.each(function() { doBlink(this,start,finish) }); };

jQuery(document).on('change', '.delete', function() {
  if ($(this).is(':checked')) {
    $(this).parents('tr').addClass('delete');
  } else {
    $(this).parents('tr').removeClass('delete');
  }
});

jQuery('#addEntry').click(function() {
  entry++;
  i = entry;
  
  content =
    '<tr class="row'+ (i%2+1) +'">'+
      '<td>'+
        '<input type="text" name="emails['+ i +'][name]" size="20">'+
      '</td>'+
      '<td>'+
        '<input type="text" name="emails['+ i +'][email]" size="30">'+
      '</td>'+
      '<td>'+
        '<select name="emails['+ i +'][group_name]" class="groups">'+
          '<option value="-1">------------</option>';
          for (var j in group_options) {
            content+= group_options[j];
          }
        content+= '</select>'+
      '</td>'+
      '<td style="text-align:center;">'+
        '<input type="checkbox" name="emails['+ i +'][active]" value="1" checked="checked">'+
      '</td>'+
      '<td style="text-align:center;">'+
        '<input type="checkbox" name="emails['+ i +'][delete]" value="1" class="delete">'+
      '</td>'+
    '</tr>'
  $('#emails').append(content);
    
  $('#addEntryContainer')
    .removeClass('row1 row2')
    .addClass('row'+ Math.abs(i%2-2))
    .appendTo($('#emails'));
});

jQuery('#addGroup').click(function() {
  name = prompt("{/literal}{'Name'|@translate}{literal}:");
  if (name != null && name != "") {
    name = name.replace(new RegExp('"','g'),"'");
    content = '<option value="'+ name +'">'+ name +'</option>';
    group_options[group_options.length] = content;
    $("select.groups").append(content).blink(1,2);
  }
});
{/literal}{/footer_script}