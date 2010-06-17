<?php
/* $Id: plugin.lang.php,v 1.11 2009/09/01 17:10:49 Criss Exp $ */
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
global $lang;

$lang['cf_plugin_name'] = 'Contact Form';
$lang['contact_form_debug'] = 'Affichage des informations de debug';

// ==================================================================
// Default values if not configured
$lang['contact_form_title'] = 'Formulaire de contact';
$lang['contact_form'] = 'Contacter';
$lang['contact_form_link'] = 'Contacter le webmestre';

// ==================================================================
// Redirect page
$lang['contact_redirect_title'] = 'Statut de l\'envoi du message';

// ==================================================================
// Menubar block
$lang['cf_from_name'] = 'Votre nom';
$lang['cf_from_mail'] = 'Votre e-mail';
$lang['cf_subject'] = 'Sujet';
$lang['cf_message'] = 'Message';
$lang['cf_submit'] = 'Envoyer';
$lang['title_send_mail'] = 'Un commentaire sur le site';

// ==================================================================
// Messages
$lang['cf_from_name_error'] = 'Veuillez entrer un nom';
$lang['cf_mail_format_error'] = $lang['reg_err_mail_address'];
$lang['cf_subject_error'] = 'Veuillez entrer un sujet';
$lang['cf_message_error'] = 'Veuillez entrer un message';
$lang['cf_error_sending_mail'] = 'Erreur lors de l\'envoi de l\'e-mail';
$lang['cf_sending_mail_successful'] = 'E-mail envoyé avec succès';
$lang['cf_form_error'] = 'Données incorrectes';
$lang['cf_inconsistent_version'] = '%s : numéros de version incohérentes';
$lang['cf_no_unlink'] = 'La fonction \'unlink\' n\'est pas disponible';
$lang['cf_unlink_errors'] = 'Des erreurs ont eu lieu lors de la suppression de fichiers';
$lang['cf_config_saved'] = 'Configuration sauvée avec succès';
$lang['cf_config_saved_with_errors'] = 'Configuration sauvée mais avec des erreurs';
$lang['cf_length_not_integer'] = 'La taille doit être un entier';
$lang['cf_delay_not_integer'] = 'Le délai doit être un entier';
$lang['cf_link_error'] = 'La variable ne peut pas contenir d\'espaces';
$lang['cf_hide'] = 'Masquer';

// ==================================================================
// Admin page
$lang['cf_validate'] = 'Valider';
// Configuration tab
$lang['cf_tab_config'] = 'Configuration';
$lang['cf_config'] = 'Configuration';
$lang['cf_config_desc'] = 'Configuration principale du plugin';
$lang['cf_label_config'] = 'Configuration générale';
$lang['cf_label_mail'] = 'Configuration de l\'e-mail';
$lang['cf_menu_link'] = 'Ajouter le lien dans le menu';
$lang['cf_guest_allowed'] = 'Autoriser les invités à avoir le formulaire';
$lang['cf_mail_prefix'] = 'Préfixe du sujet de l\'e-mail envoyé';
$lang['cf_separator'] = 'Caractère(s) utilisé(s) pour définir une barre de séparation dans l\'e-mail au format texte';
$lang['cf_separator_length'] = 'Taille de la barre de séparation';
$lang['cf_mandatory_name'] = 'Présence du nom obligatoire';
$lang['cf_mandatory_mail'] = 'Présence de l\'e-mail obligatoire';
$lang['cf_redirect_delay'] = 'Délai de pause de la redirection';
$lang['cf_label_link'] = 'Gestion du lien (hors menu) vers le formulaire';
$lang['cf_define_link'] = 'Définir le lien';
$lang['cf_link'] = 'Nom de la variable de template contenant le lien HTML vers le formulaire de contact';
// Emails tab
$lang['cf_tab_emails'] = 'E-mails';
$lang['cf_emails'] = 'E-mails';
$lang['cf_emails_desc'] = 'Gestion des e-mails de destination';
$lang['cf_active'] = 'E-mail actif';
$lang['cf_no_mail'] = 'Aucune adresse e-mail disponible';
$lang['cf_refresh'] = 'Regénérer la liste des adresses';
// Language tab
$lang['cf_tab_language'] = 'Localisation';
$lang['cf_language'] = 'Localisation';
$lang['cf_language_desc'] = 'Traduction des messages';
$lang['cf_select_item'] = 'Sélectionnez l\'élément à traduire';
$lang['cf_default_lang'] = 'Par défaut';
$lang['contact_form_title_label'] = 'Titre dans la barre de menus';
$lang['contact_form_label'] = 'Nom affiché dans la barre de menus';
$lang['contact_form_link_label'] = 'Texte utilisé pour le lien de contact en bas de page';
// History tab
$lang['cf_tab_history'] = 'Historique';
$lang['cf_history'] = 'Historique';
$lang['cf_history_desc'] = 'Historique des modifications';
$lang['cf_history_date'] = 'Date';
$lang['cf_history_version'] = 'Version';
$lang['cf_history_log'] = 'Changelog';
$lang['cf_file_not_found'] = 'Fichier non trouvé';
$lang['cf_file_empty'] = 'Fichier vide';
$lang['cf_format_date'] = '%D %M %Y';
?>