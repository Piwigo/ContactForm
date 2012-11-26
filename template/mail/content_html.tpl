<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Piwigo Mail</title>
  <meta http-equiv="Content-Type" content="text/html; charset={$CONTENT_ENCODING}">
  
  <style type="text/css">{strip}<!--
  {$CONTACT_MAIL_CSS}
  -->{/strip}</style>
</head>

<body>
<div id="the_page">

<div id="the_header">
{$cf_prefix}
</div>

<div id="the_content">
  <p>
    <b>{'Name'|@translate}:</b> {$contact.author}<br>
    <b>{'Email address'|@translate}:</b> {$contact.email}
    {if $contact.show_ip}<br>{'IP: %s'|@translate|@sprintf:$contact.ip}{/if}
  </p>

  <blockquote>{$contact.content}</blockquote>
</div>

<div id="the_footer">
  {'Sent by'|@translate} <a href="{$GALLERY_URL}">{$GALLERY_TITLE}</a>
  - {'Powered by'|@translate} <a href="{$PHPWG_URL}" class="Piwigo">Piwigo</a> {$VERSION}
  - Contact Form
</div>

</div>
</body>
</html>