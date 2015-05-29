{combine_css path=$CONTACT_FORM_PATH|cat:"admin/template/style.css"}

{combine_script id='LocalStorageCache' load='footer' path='admin/themes/default/js/LocalStorageCache.js'}

{combine_script id='jquery.selectize' load='footer' path='themes/default/js/plugins/selectize.min.js'}
{combine_css id='jquery.selectize' path="themes/default/js/plugins/selectize.{$themeconf.colorscheme}.css"}

{footer_script}
(function(){
{* <!-- USERS --> *}
var usersCache = new UsersCache({
  serverKey: '{$CACHE_KEYS.users}',
  serverId: '{$CACHE_KEYS._hash}',
  rootUrl: '{$ROOT_URL}'
});

usersCache.selectize(jQuery('[data-selectize=users]'));

$('#users, #emails').on('click', '[data-delete]', function() {
  var row = $(this).closest('tr');
  $.ajax({
    url: 'ws.php?method=pwg.cf.delete&format=json',
    method: 'post',
    dataType: 'json',
    data: {
      id: $(this).data('delete')
    },
    success: function(data) {
      if (data.stat == 'ok') {
        row.remove();
      }
      else {
        alert(data.message);
      }
    }
  });
});

$('[name=add_user]').on('click', function() {
  $.ajax({
    url: 'ws.php?method=pwg.cf.addUser&format=json',
    method: 'post',
    dataType: 'json',
    data: {
      user_id: $('[data-selectize=users]').val()
    },
    success: function(data) {
      if (data.stat == 'ok') {
        addLine($('#users'), data.result);
      }
      else {
        alert(data.message);
      }
    }
  });
});

$('[name=add_email]').on('click', function() {
  $.ajax({
    url: 'ws.php?method=pwg.cf.addEmail&format=json',
    method: 'post',
    dataType: 'json',
    data: {
      name: $('[name=new_name]').val(),
      email: $('[name=new_email]').val()
    },
    success: function(data) {
      if (data.stat == 'ok') {
        addLine($('#emails'), data.result);
      }
      else {
        alert(data.message);
      }
    }
  });
});

function addLine(table, data) {
  table.append('<tr>'+
    '<td>'+data.name+'</td>'+
    '<td>'+data.email+'</td>'+
    '<td><i class="icon-cancel-circled" data-delete="'+data.id+'"></i></td>'+
  '</tr>');
}

}());
{/footer_script}

<div class="titrePage">
	<h2>Contact Form</h2>
</div>

<form class="properties">
<fieldset>
  <legend>{'Manage contact e-mails'|translate}</legend>
  
  <table class="doubleSelect">
    <tr>
      <td>
        <h3>{'Users'|translate}</h3>
        
        <table class="table2" id="users">
          <tr class="throw">
            <th>{'Name'|translate}</th>
            <th>{'Email address'|translate}</th>
            <th class="delete-row"></th>
          </tr>
        {foreach from=$USERS item=entry}
          <tr>
            <td>{$entry.name}</td>
            <td>{$entry.email}</td>
            <td><i class="icon-cancel-circled" data-delete="{$entry.id}"></i></td>
          </tr>
        {/foreach}
        </table>
        
        <div id="add-user">
          <select name="new_user" data-selectize="users" placeholder="{'Select a new user'|translate|escape:html}"></select>
          <input type="button" name="add_user" value="{'Add'|translate}" class="submit">
        </div>
      </td>

      <td>
        <h3>{'Additional emails'|translate}</h3>
  
        <table class="table2" id="emails">
          <tr class="throw">
            <th>{'Name'|translate}</th>
            <th>{'Email address'|translate}</th>
            <th class="delete-row"></th>
          </tr>
        {foreach from=$EMAILS item=entry}
          <tr>
            <td>{$entry.name}</td>
            <td>{$entry.email}</td>
            <td><i class="icon-cancel-circled" data-delete="{$entry.id}"></i></td>
          </tr>
        {/foreach}
        </table>
        
        <div id="add-email">
          <input type="text" name="new_name" placeholder="{'New name'|translate|escape:html}" size="20">
          <input type="text" name="new_email" placeholder="{'New e-mail'|translate|escape:html}" size="30">
          <input type="button" name="add_email" value="{'Add'|translate}" class="submit">
        </div>
      </td>
    </tr>
  </table>
</form>