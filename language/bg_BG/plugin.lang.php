<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
global $lang;

$lang['cf_plugin_name'] = 'Contact Form';
$lang['contact_form_debug'] = 'Display of debug information';

// ==================================================================
// Default values if not configured
$lang['contact_form_title'] = 'Contact form';
$lang['contact_form'] = 'Контакт';
$lang['contact_form_link'] = 'Контакт Уебмастър';

// ==================================================================
// Redirect page
$lang['contact_redirect_title'] = 'Send message status';

// ==================================================================
// Menubar block
$lang['cf_from_name'] = 'Вашето Име';
$lang['cf_from_mail'] = 'Вашият e-mail';
$lang['cf_subject'] = 'Тема';
$lang['cf_message'] = 'Съобщение';
$lang['cf_submit'] = 'Изпрати';
$lang['title_send_mail'] = 'Коментар на сайта';

// ==================================================================
// Messages
$lang['cf_from_name_error'] = 'Моля въведете име';
$lang['cf_mail_format_error'] = $lang['mail address must be like xxx@yyy.eee (example : jack@altern.org)'];
$lang['cf_subject_error'] = 'Моля въведете тема';
$lang['cf_message_error'] = 'Моля въведете съобщение';
$lang['cf_error_sending_mail'] = 'Грешка при изпращане на e-mail';
$lang['cf_sending_mail_successful'] = 'E-mail е изпратен успешно';
$lang['cf_form_error'] = 'Невалидна информация';
$lang['cf_no_unlink'] = 'Функцията \'unlink\' не е налична...';
$lang['cf_unlink_errors'] = 'Възникна грешка при изтриването на файла';
$lang['cf_config_saved'] = 'Конфигурацията е записана успешно';
$lang['cf_config_saved_with_errors'] = 'Конфигурацията е записана с грешки';
$lang['cf_length_not_integer'] = 'Размерът трябва да бъде цяло число';
$lang['cf_delay_not_integer'] = 'Закъснението трябва да бъде цяло число';
$lang['cf_link_error'] = 'Променливата неможе да съдържа интервали';
$lang['cf_hide'] = 'Скрий';

// ==================================================================
// Admin page
$lang['cf_validate'] = 'Изпрати';
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
// Emails tab
$lang['cf_tab_emails'] = 'E-mails';
$lang['cf_emails'] = 'E-mails';
$lang['cf_emails_desc'] = 'Destination e-mails management';
$lang['cf_active'] = 'Active e-mail';
$lang['cf_no_mail'] = 'No e-mail address available';
$lang['cf_refresh'] = 'Regenerate e-mail list address';
?>
