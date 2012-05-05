<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

$lang['cf_plugin_name'] = 'Kontaktformulär';
$lang['contact_form_debug'] = 'Visning av felsökningsinformation';

// ==================================================================
// Default values if not configured
$lang['contact_form_title'] = 'Kontaktformulär';
$lang['contact_form'] = 'Kontakt';
$lang['contact_form_link'] = 'Kontakta webbansvarig';

// ==================================================================
// Redirect page
$lang['contact_redirect_title'] = 'Skicka meddelande status';

// ==================================================================
// Menubar block
$lang['cf_from_name'] = 'Namn';
$lang['cf_from_mail'] = 'E-post';
$lang['cf_subject'] = 'Ämne';
$lang['cf_message'] = 'Meddelande';
$lang['cf_submit'] = 'skicka';
$lang['title_send_mail'] = 'En kommentar till webbsidan';

// ==================================================================
// Messages
$lang['cf_from_name_error'] = 'Ange ett namn';
$lang['cf_mail_format_error'] = 'e-postadress måste vara av formen ”arne@anka.org';
$lang['cf_subject_error'] = 'Ange ett ämnet';
$lang['cf_message_error'] = 'Skriv ett meddelande';
$lang['cf_error_sending_mail'] = 'Fel vid sändning av e-post';
$lang['cf_sending_mail_successful'] = 'E-post har skickats';
$lang['cf_form_error'] = 'Ogiltiga data';
$lang['cf_no_unlink'] = 'Funktionen \'unlink\' inte tillgänglig...';
$lang['cf_unlink_errors'] = 'Fel inträffade vid borttagning av filer';
$lang['cf_config_saved'] = 'Inställningar har sparats';
$lang['cf_config_saved_with_errors'] = 'Inställningar har sparats med fel!';
$lang['cf_length_not_integer'] = 'Storleken måste vara ett heltal';
$lang['cf_delay_not_integer'] = 'Fördröjningen måste vara ett heltal';
$lang['cf_link_error'] = 'Variabel kan inte  innehålla mellanslag';
$lang['cf_hide'] = 'Dölj';

// ==================================================================
// Admin page
$lang['cf_validate'] = 'Skicka';
// Configuration tab
$lang['cf_tab_config'] = 'Inställningar';
$lang['cf_config'] = 'Inställningar';
$lang['cf_config_desc'] = 'Allmänna inställningar för plugg-in';
$lang['cf_label_config'] = 'Allmäna inställningar';
$lang['cf_label_mail'] = 'E-post inställningar';
$lang['cf_menu_link'] = 'Inkludera länk i menyn';
$lang['cf_guest_allowed'] = 'Tillåt gäster att använda formuläret';
$lang['cf_mail_prefix'] = 'Prefix till "Ämne" i det sända e-postmeddelandet';
$lang['cf_separator'] = 'Symbol att använda som avdelare i e-post i textformat';
$lang['cf_separator_length'] = 'Avdelarens längd';
$lang['cf_mandatory_name'] = 'Obligatoriskt namn';
$lang['cf_mandatory_mail'] = 'Obligatorisk e-postadress';
$lang['cf_redirect_delay'] = 'Fördröjning av omdirigering';
// Emails tab
$lang['cf_tab_emails'] = 'E-post';
$lang['cf_emails'] = 'E-post';
$lang['cf_emails_desc'] = 'Destinationshantering för e-post';
$lang['cf_active'] = 'Aktiv e-post';
$lang['cf_no_mail'] = 'Ingen e-postadress tillgänglig';
$lang['cf_refresh'] = 'Uppdatera';
?>
