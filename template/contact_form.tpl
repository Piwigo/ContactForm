{combine_css path=$CONTACT_FORM_PATH|cat:"template/style.css"}
{combine_script id="livevalidation" load="footer" path=$CONTACT_FORM_PATH|cat:"template/livevalidation.min.js"}

{footer_script require='livevalidation'}
(function(){
  {if $contact.mandatory_name and !$contact.is_logged}
  var author = new LiveValidation('author', {ldelim} onlyOnSubmit: true });
  author.add(Validate.Presence, {ldelim} failureMessage: "{'Please enter a name'|translate}" });
  {/if}

  {if $contact.mandatory_mail and (!$contact.is_logged or empty($contact.email))}
  var email = new LiveValidation('email', {ldelim} onlyOnSubmit: true });
  email.add(Validate.Presence, {ldelim} failureMessage: "{'Please enter an e-mail'|translate}" });
  email.add(Validate.Email, {ldelim} failureMessage: "{'mail address must be like xxx@yyy.eee (example : jack@altern.org)'|translate}" });
  {/if}

  {if $GROUPS}
  var group = new LiveValidation('group', {ldelim} onlyOnSubmit: true })
  group.add(Validate.Exclusion, {ldelim} within: ['-1'], failureMessage: "{'Please choose a category'|translate}" });
  {/if}

  var subject = new LiveValidation('subject', {ldelim} onlyOnSubmit: true });
  subject.add(Validate.Presence, {ldelim} failureMessage: "{'Please enter a subject'|translate}" });
  subject.add(Validate.Length, {ldelim} maximum: 100,
    tooLongMessage: "{'%s must not be more than %d characters long'|translate:'':100}"
    });

  var content = new LiveValidation('cf_content', {ldelim} onlyOnSubmit: true });
  content.add(Validate.Presence, {ldelim} failureMessage: "{'Please enter a message'|translate}" });
  content.add(Validate.Length, {ldelim} maximum: 2000,
    tooLongMessage: "{'%s must not be more than %d characters long'|translate:'':2000}",
    });
}());
{/footer_script}


{if $ContactForm_before}
<div class="contact desc">{$ContactForm_before}</div>
{/if}

<div class="contact">
  <form  method="post" action="{$F_ACTION}">
    <table>
      <tr>
        <td class="title"><label for="author">{'Your name'|translate}</label></td>
        <td>
        {if $contact.is_logged}
          {$contact.author}
          <input type="hidden" name="author" value="{$contact.author|escape:html}">
        {else}
          <input type="text" name="author" id="author" size="40" value="{$contact.author|escape:html}">
        {/if}
        </td>
      </tr>
      <tr>
        <td class="title"><label for="email">{'Your e-mail'|translate}</label></td>
        <td>
        {if $contact.is_logged and !empty($contact.email)}
          {$contact.email}
          <input type="hidden" name="email" value="{$contact.email|escape:html}">
        {else}
          <input type="text" name="email" id="email" size="40" value="{$contact.email|escape:html}">
        {/if}
        </td>
      </tr>
    {if $GROUPS}
      <tr>
        <td class="title"><label for="group">{'Category'|translate}</label></td>
        <td>
          <select name="group" id="group">
            <option value="-1">------------</option>
            {html_options options=$GROUPS selected=$contact.group}
          </select>
        </td>
      </tr>
    {/if}
      <tr>
        <td class="title"><label for="subject">{'Subject'|translate}</label></td>
        <td><input type="text" name="subject" id="subject" style="width:400px;" value="{$contact.subject|escape:html}"></td>
      </tr>
      <tr>
        <td class="title"><label for="cf_content">{'Message'|translate}</label></td>
        <td><textarea name="content" id="cf_content" rows="10" style="width:400px;">{$contact.content}</textarea></td>
      </tr>
    {if isset($CRYPTO)}
      {$CRYPTO.parsed_content}
    {/if}
    {if isset($EASYCAPTCHA)}
      {$EASYCAPTCHA.parsed_content}
    {/if}
      <tr>
        <td class="title">&nbsp;</td>
        <td>
          <input class="submit" type="submit" name="send_mail" value="{'Send'|translate}">
          <label><input type="checkbox" name="send_copy"> {'Send copy to my email'|translate}</label>
        </td>
      </tr>
    </table>

    <input type="hidden" name="key" value="{$KEY}" />
  </form>
</div>

{if $ContactForm_after}
<div class="contact desc">{$ContactForm_after}</div>
{/if}