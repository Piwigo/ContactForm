{$cf_prefix}
--------------------

{'Name'|@translate} : {$contact.author}
{'Email address'|@translate} : {$contact.email}

====================
{$contact.content}
====================

{'IP: %s'|@translate|@sprintf:$contact.ip}
{'Browser: %s'|@translate|@sprintf:$contact.agent}

--------------------
{'Sent by'|@translate} "{$GALLERY_TITLE}" {$GALLERY_URL}
{'Powered by'|@translate} Piwigo {$VERSION} - Contact Form