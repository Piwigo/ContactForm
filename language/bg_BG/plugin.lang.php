<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
global $lang;

$lang['cf_plugin_name'] = 'Формуляр за контакт';
$lang['contact_form_debug'] = 'Покажи информацията за дебъгване';

// ==================================================================
// Default values if not configured
$lang['contact_form_title'] = 'Формуляр за контакт';
$lang['contact_form'] = 'Контакт';
$lang['contact_form_link'] = 'Контакт Уебмастър';

// ==================================================================
// Redirect page
$lang['contact_redirect_title'] = 'Изпрати съобщение за състоянието';

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
$lang['cf_tab_config'] = 'Конфигурация';
$lang['cf_config'] = 'Конфигурация';
$lang['cf_config_desc'] = 'Плъгин за основната конфигурация';
$lang['cf_label_config'] = 'Обща конфигурация';
$lang['cf_label_mail'] = 'Е-мейл конфигурация';
$lang['cf_menu_link'] = 'Добави линк в менюто';
$lang['cf_guest_allowed'] = 'Позволи на гостите да виждат формата';
$lang['cf_mail_prefix'] = 'Prefix of the sent e-mail subject';
$lang['cf_separator'] = 'Character(s) used to define a separation bar in the e-mail in text format';
$lang['cf_separator_length'] = 'Размер на бара';
$lang['cf_mandatory_name'] = 'Името е задължително';
$lang['cf_mandatory_mail'] = 'Е-мейл адресът е задължителен';
$lang['cf_redirect_delay'] = 'Pause delay of redirection';
// Emails tab
$lang['cf_tab_emails'] = 'Е-мейли';
$lang['cf_emails'] = 'Е-мейли';
$lang['cf_emails_desc'] = 'Destination e-mails management';
$lang['cf_active'] = 'Активен Е-майл';
$lang['cf_no_mail'] = 'Няма наличен Е-мейл адрес';
$lang['cf_refresh'] = 'Регенериране на списъка с Е-мейл адреси';
?>
