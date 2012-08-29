{$cf_prefix}

--------------------
{'Name'|@translate}: {$contact.author}
{'Email address'|@translate}: {$contact.email}
{if $contact.show_ip}{'IP: %s'|@translate|@sprintf:$contact.ip}{/if}

--------------------
{$contact.content}


--------------------
{'Sent by'|@translate} "{$GALLERY_TITLE}" {$GALLERY_URL}
{'Powered by'|@translate} Piwigo {$VERSION} - Contact Form