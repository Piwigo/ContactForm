<style type="text/css">{strip}

{/strip}</style>

<p>
  <b>{'Name'|translate}:</b> {$CONTACT.author}<br>
  <b>{'Email address'|translate}:</b> {$CONTACT.email}
  {if $CONTACT.show_ip}
    <br>{'IP: %s'|translate:$CONTACT.ip}
    <br>{'Browser: %s'|translate:$CONTACT.agent}
  {/if}
</p>

<blockquote>{$CONTACT.content}</blockquote>