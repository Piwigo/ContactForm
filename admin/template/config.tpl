{combine_css path=$CONTACT_FORM_PATH|cat:"admin/template/style.css"}

<div class="titrePage">
	<h2>Contact Form</h2>
</div>

<form method="post" action="{$CONTACT_FORM_ADMIN}-config" class="properties">
  <fieldset>
    <legend>{'General configuration'|translate}</legend>
    <ul>
      <li>
        <label>
          <input type="checkbox" name="cf_menu_link" value="1" {if $cf_menu_link}checked="checked"{/if}>
          {'Add link in menu'|translate}
        </label>
      </li>
      <li>
        <label>
          <input type="checkbox" name="cf_allow_guest" value="1" {if $cf_allow_guest}checked="checked"{/if}>
          {'Allow guests to see the form'|translate}
        </label>
      </li>
      <li>
        <label>
          <input type="checkbox" name="cf_mandatory_mail" value="1" {if $cf_mandatory_mail}checked="checked"{/if}>
          {'E-mail address is mandatory'|translate}
        </label>
      </li>
      <li>
        <label>
          <input type="checkbox" name="cf_mandatory_name" value="1" {if $cf_mandatory_name}checked="checked"{/if}>
          {'Name is mandatory'|translate}
        </label>
      </li>
      <li>
        <label>
          <input type="text" name="cf_redirect_url" value="{if $cf_redirect_url}{$cf_redirect_url}{else}http://{/if}" size="50">
          {'Redirect after sending email (optional)'|translate}
        </label>
      </li>
    </ul>
  </fieldset>

  <fieldset>
    <legend>{'E-mail configuration'|translate}</legend>
    <ul>
      <li>
        {'E-mail format :'|translate}
        <label>
          <input type="radio" name="cf_mail_type" value="text/html" {if $cf_mail_type == 'text/html'}checked="checked"{/if}>
          HTML
        </label>
        <label>
          <input type="radio" name="cf_mail_type" value="text/plain" {if $cf_mail_type == 'text/plain'}checked="checked"{/if}>
          {'Plain text'|translate}
        </label>
      </li>
      <li>
        <label>
          <input type="text" name="cf_default_subject" value="{$cf_default_subject|escape:html}" size="50">
          {'Default e-mail subject'|translate} ({'can be translated with LocalFiles Editor plugin'|translate})
        </label>
      </li>
      <li>
        <label>
          <input type="text" name="cf_subject_prefix" value="{$cf_subject_prefix|escape:html}" size="50">
          {'Prefix of the sent e-mail subject'|translate} ({'you can use "%gallery_title%"'|translate})
        </label>
      </li>
    </ul>
  </fieldset>

  <fieldset>
    <legend>{'Text before the contact form'|translate}</legend>
    <textarea rows="4" cols="80" class="description" name="cf_before">{$cf_before}</textarea>
  </fieldset>

  <fieldset>
    <legend>{'Text after the contact form'|translate}</legend>
    <textarea rows="4" cols="80" class="description" name="cf_after">{$cf_after}</textarea>
  </fieldset>

  <p style="text-align:left;"><input type="submit" name="save_config" value="{'Submit'|translate}" class="submit"></p>
</form>