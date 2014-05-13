{'Name'|translate}: {$CONTACT.author}
{'Email address'|translate}: {$CONTACT.email}
{if $CONTACT.show_ip}{'IP: %s'|translate:$CONTACT.ip}
{'Browser: %s'|translate:$CONTACT.agent}{/if}

--------------------
{$CONTENT}