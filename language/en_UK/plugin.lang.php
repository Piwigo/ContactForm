<?php
/* $Id: plugin.lang.php,v 1.11 2009/09/01 17:10:50 Criss Exp $ */
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
global $lang;

$lang['cf_plugin_name'] = 'Contact Form';
$lang['contact_form_debug'] = 'Display of debug information';

// ==================================================================
// Default values if not configured
$lang['contact_form_title'] = 'Contact form';
$lang['contact_form'] = 'Contact';
$lang['contact_form_link'] = 'Contact webmaster';

// ==================================================================
// Redirect page
$lang['contact_redirect_title'] = 'Send message status';

// ==================================================================
// Menubar block
$lang['cf_from_name'] = 'Your name';
$lang['cf_from_mail'] = 'Your e-mail';
$lang['cf_subject'] = 'Subject';
$lang['cf_message'] = 'Message';
$lang['cf_submit'] = 'Send';

// ==================================================================
// Messages
$lang['cf_from_name_error'] = 'Please enter a name';
$lang['cf_mail_format_error'] = $lang['reg_err_mail_address'];
$lang['cf_subject_error'] = 'Please enter a subject';
$lang['cf_message_error'] = 'Please enter a message';
$lang['cf_error_sending_mail'] = 'Error while sending e-mail';
$lang['cf_sending_mail_successful'] = 'E-mail sent successfully';
$lang['cf_form_error'] = 'Invalid data';
$lang['cf_inconsistent_version'] = '%s: inconsistent version numbers';
$lang['cf_no_unlink'] = 'Function \'unlink\' not available...';
$lang['cf_unlink_errors'] = 'Error occured during file deletion';
$lang['cf_config_saved'] = 'Configuration successfully saved';
$lang['cf_config_saved_with_errors'] = 'Configuration saved with errors';
$lang['cf_length_not_integer'] = 'Size must be an integer';
$lang['cf_delay_not_integer'] = 'Delay must be an integer';
$lang['cf_link_error'] = 'Variable can\'t contain spaces';
$lang['cf_hide'] = 'Hide';

// ==================================================================
// Admin page
$lang['cf_validate'] = 'Submit';
// Configuration tab
$lang['cf_tab_config'] = 'Configuration';
$lang['cf_config'] = 'Configuration';
$lang['cf_config_desc'] = 'Plugin main configuration';
$lang['cf_label_config'] = 'General configuration';
$lang['cf_label_mail'] = 'E-mail configuration';
$lang['cf_menu_link'] = 'Add link in menu';
$lang['cf_guest_allowed'] = 'Allow guests to see the form';
$lang['cf_mail_prefix'] = 'Prefix of the sent e-mail subject';
$lang['cf_separator'] = 'Character(s) used to define a separation bar in the e-mail in text format';
$lang['cf_separator_length'] = 'Size of the bar';
$lang['cf_mandatory_name'] = 'Name is mandatory';
$lang['cf_mandatory_mail'] = 'E-mail address is mandatory';
$lang['cf_redirect_delay'] = 'Pause delay of redirection';
$lang['cf_label_link'] = 'Link management (outside menubar)';
$lang['cf_define_link'] = 'Define link';
$lang['cf_link'] = 'Name of the template variable containing the HTML link to the contact form';
// Emails tab
$lang['cf_tab_emails'] = 'E-mails';
$lang['cf_emails_desc'] = 'Destination e-mails management';
$lang['cf_active'] = 'Active e-mail';
$lang['cf_no_mail'] = 'No e-mail address available';
$lang['cf_refresh'] = 'Regenerate e-mail list address';
// Language tab
$lang['cf_tab_language'] = 'Localization';
$lang['cf_language_desc'] = 'Messages translation';
$lang['cf_select_item'] = 'Select item to translate';
$lang['cf_default_lang'] = 'Default';
$lang['contact_form_title_label'] = 'Title used in menubar';
$lang['contact_form_label'] = 'Name used in menubar';
$lang['contact_form_link_label'] = 'Text used for contact link in the page footer';
// History tab
$lang['cf_tab_history'] = 'History';
$lang['cf_history'] = 'History';
$lang['cf_history_desc'] = 'Changes history';
$lang['cf_history_date'] = 'Date';
$lang['cf_history_version'] = 'Version';
$lang['cf_history_log'] = 'Changelog';
$lang['cf_file_not_found'] = 'File not found';
$lang['cf_file_empty'] = 'File is empty';
$lang['cf_format_date'] = '%M %D, %Y';
?>