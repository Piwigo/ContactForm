<?php
/* $Id: plugin.lang.php,v 1.11 2009/09/01 17:10:50 Criss Exp $ */
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
global $lang;

$lang['cf_plugin_name'] = 'Contact Form';
$lang['contact_form_debug'] = 'Visualizzare le informazioni di debug';

// ==================================================================
// Default values if not configured
$lang['contact_form_title'] = 'Modulo di contatto';
$lang['contact_form'] = 'Contattare';
$lang['contact_form_link'] = 'Contattare il webmaster';

// ==================================================================
// Redirect page
$lang['contact_redirect_title'] = 'Stato d\'invio del messaggio';

// ==================================================================
// Menubar block
$lang['cf_from_name'] = 'Il vostro nome';
$lang['cf_from_mail'] = 'La vostra E-mail';
$lang['cf_subject'] = 'Soggetto';
$lang['cf_message'] = 'Messaggio';
$lang['cf_submit'] = 'Inviare';

// ==================================================================
// Messages
$lang['cf_from_name_error'] = 'Inserire un nome';
$lang['cf_mail_format_error'] = $lang['reg_err_mail_address'];
$lang['cf_subject_error'] = 'Inserire un soggetto';
$lang['cf_message_error'] = 'Inserire un messaggio';
$lang['cf_error_sending_mail'] = 'Errore durante l\'invio dell\'E-mail'; 
$lang['cf_sending_mail_successful'] = 'Invio dell\'E-mail riuscito';
$lang['cf_form_error'] = 'Dati errati'; 
$lang['cf_inconsistent_version'] = '%s : versioni incoerenti';
$lang['cf_no_unlink'] = 'La funzione \'unlink\' non è disponibile';
$lang['cf_unlink_errors'] = 'Errori durante la sopressione dei file';
$lang['cf_config_saved'] = 'Configurazione salvata con successo';
$lang['cf_config_saved_with_errors'] = 'Configurazione salvata con errori';
$lang['cf_length_not_integer'] = 'Le dimensioni devono essere un numero intero';
$lang['cf_delay_not_integer'] = 'Il limite deve essere un numero intero';
$lang['cf_link_error'] = 'La variabile non può contenere degli spazi';
$lang['cf_hide'] = 'Nascondere';

// ==================================================================
// Admin page
$lang['cf_validate'] = 'Confermare';
// Configuration tab
$lang['cf_tab_config'] = 'Configurazione';
$lang['cf_config'] = 'Configurazione';
$lang['cf_config_desc'] = 'Configurazione principale del plugin';
$lang['cf_label_config'] = 'Configurazione generale';
$lang['cf_label_mail'] = 'Configurazione dell\'E-mail';
$lang['cf_menu_link'] = 'Aggiungere un link nel menu';
$lang['cf_guest_allowed'] = 'Autorizzare gli ospiti ad accedere al modulo';
$lang['cf_mail_prefix'] = 'Prefisso dell\'E-mail inviata';
$lang['cf_separator'] = 'Carattere(i) usato(i) per definire una barra di separazione nella E-mail al formato testo';
$lang['cf_separator_length'] = 'Dimenzioni della barra di separazione';
$lang['cf_mandatory_name'] = 'Nome obbligatorio';
$lang['cf_mandatory_mail'] = 'E-mail obbligatoria';
$lang['cf_redirect_delay'] = 'Limite d\'attesa ';
$lang['cf_label_link'] = 'Gestione del link (escluso quello del menu) verso il modulo';
$lang['cf_define_link'] = 'Definire il link';
$lang['cf_link'] = 'Nome della variabile del template contenente il link HTML verso il modulo di contatto';
// Emails tab
$lang['cf_tab_emails'] = 'E-mails';
$lang['cf_emails'] = 'E-mails';
$lang['cf_emails_desc'] = 'Gestione delle e-mail di destinazione';
$lang['cf_active'] = 'E-mail attiva';
$lang['cf_no_mail'] = 'Nessun\'indirizzo e-mail disponibile';
$lang['cf_refresh'] = 'Rigenerare l\'elenco degli indirizzi';
// Language tab
$lang['cf_tab_language'] = 'Localizzazione';
$lang['cf_language'] = 'Localizzazione';
$lang['cf_language_desc'] = 'Traduzzione dei messaggi';
$lang['cf_select_item'] = 'Selezzionare l\'elemento da tradurre';
$lang['cf_default_lang'] = 'Di default';
$lang['contact_form_title_label'] = 'Titolo nella barra dei menu';
$lang['contact_form_label'] = 'Nome visualizzato nella barra dei menu';
$lang['contact_form_link_label'] = 'Testo utilizzato per il link di contatto nel footer';
// History tab
$lang['cf_tab_history'] = 'Cronologia';
$lang['cf_history'] = 'Cronologia';
$lang['cf_history_desc'] = 'Cronologia delle modifiche';
$lang['cf_history_date'] = 'Data';
$lang['cf_history_version'] = 'Versione';
$lang['cf_history_log'] = 'Changelog';
$lang['cf_file_not_found'] = 'File non trovato';
$lang['cf_file_empty'] = 'File vuoto';
$lang['cf_format_date'] = '%D %M %Y';
?>