{combine_css path=$CONTACT_FORM_PATH|@cat:"admin/template/style.css"}

{footer_script}{literal}
jQuery(document).on('change', '.delete', function() {
  if ($(this).is(':checked')) {
    $(this).parents('tr').addClass('delete');
  } else {
    $(this).parents('tr').removeClass('delete');
  }
});
{/literal}{/footer_script}

<div class="titrePage">
	<h2>Contact Form</h2>
</div>

<form method="post" action="{$CONTACT_FORM_ADMIN}-emails" class="properties">
  <table class="table2" id="emails">
    <tr class="throw">
      <th>{'Name'|@translate}</th>
      <th>{'Email address'|@translate}</th>
      <th>{'Active'|@translate}</th>
      <th>{'Delete'|@translate}</th>
    </tr>
  {counter start=0 assign=i}
  {foreach from=$EMAILS item=entry}
    <tr class="{if $i is odd}row1{else}row2{/if}">
      <td>
        <input type="text" name="emails[{$i}][name]" value="{$entry.name}" size="20">
      </td>
      <td>
        <input type="text" name="emails[{$i}][email]" value="{$entry.email}" size="30">
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
      <td colspan="4">
        <a id="addEntry">{'+ Add an email'|@translate}</a>
      </td>
    </tr>
  </table>
  {footer_script}var entry = {$i};{/footer_script}

  <p><input type="submit" name="save_emails" value="{'Submit'|@translate}" class="submit"></p>
</form>

{footer_script}{literal}
jQuery('#addEntry').click(function() {
  entry++;
  i = entry;
  
  $('#emails').append(
    '<tr class="row'+ (i%2+1) +'">'+
      '<td>'+
        '<input type="text" name="emails['+ i +'][name]" size="20">'+
      '</td>'+
      '<td>'+
        '<input type="text" name="emails['+ i +'][email]" size="30">'+
      '</td>'+
      '<td style="text-align:center;">'+
        '<input type="checkbox" name="emails['+ i +'][active]" value="1" checked="checked">'+
      '</td>'+
      '<td style="text-align:center;">'+
        '<input type="checkbox" name="emails['+ i +'][delete]" value="1" class="delete">'+
      '</td>'+
    '</tr>'
    );
    
  $('#addEntryContainer')
    .removeClass('row1 row2')
    .addClass('row'+ Math.abs(i%2-2))
    .appendTo($('#emails'));
});
{/literal}{/footer_script}