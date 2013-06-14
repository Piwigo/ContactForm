<?php
if (!defined('CONTACT_FORM_PATH')) die('Hacking attempt!');

/**
 * define page section from url
 */
function contact_form_section_init()
{
  global $tokens, $page, $conf;
  
  if ($tokens[0] == 'contact')
  {
    add_event_handler('loc_begin_page_header', 'contact_form_header');
    
    $page['section'] = 'contact';
    $page['is_homepage'] = false;
    
    $page['title'] = l10n('Contact');
    $page['section_title'] = $page['section_title'] = '<a href="'.get_absolute_root_url().'">'.l10n('Home').'</a>'.$conf['level_separator'].'<a href="'.CONTACT_FORM_PUBLIC.'">'.l10n('Contact').'</a>';  
  }
}
function contact_form_header()
{
  global $page;
  $page['body_id'] = 'theContactPage';
}

/**
 * contact page 
 */
function contact_form_page()
{
  global $page;

  if (isset($page['section']) and $page['section'] == 'contact')
  {
    include(CONTACT_FORM_PATH . '/include/contact_form.inc.php');
  }
}

/** 
 * public menu link 
 */
function contact_form_applymenu($menu_ref_arr)
{
  global $conf;
  
  if ( !$conf['ContactForm']['cf_menu_link'] ) return;
  if ( !is_classic_user() and !$conf['ContactForm']['cf_allow_guest'] ) return;
  if ( !count(get_contact_emails()) ) return;

  $menu = &$menu_ref_arr[0];
  if (($block = $menu->get_block('mbMenu')) != null)
  {
    array_push($block->data, array(
      'URL' => CONTACT_FORM_PUBLIC,
      'NAME' => l10n('Contact'),
    ));
  }
}

/**
 * change contact on link on footer
 */
function contact_form_footer_link($content, &$smarty)
{
  $search = '<a href="mailto:{$CONTACT_MAIL}?subject={\'A comment on your site\'|@translate|@escape:url}">';
  $replace = '<a href="'.CONTACT_FORM_PUBLIC.'">';
  return str_replace($search, $replace, $content);
}

/**
 * init emails list
 */
function contact_form_initialize_emails()
{
  global $conf;
  
  $query = '
SELECT
    u.'.$conf['user_fields']['username'].' AS username,
    u.'.$conf['user_fields']['email'].' AS email
  FROM '.USERS_TABLE.' AS u
    JOIN '.USER_INFOS_TABLE.' AS i
    ON i.user_id =  u.'.$conf['user_fields']['id'].'
  WHERE i.status in (\'webmaster\',  \'admin\')
    AND '.$conf['user_fields']['email'].' IS NOT NULL
  ORDER BY username
;';
  $result = pwg_query($query);
  
  $emails = array();
  while ($row = pwg_db_fetch_assoc($result))
  {
    array_push($emails, array(
      'name' => $row['username'],
      'email' => $row['email'],
      'active' => 'true',
      ));
  }
  
  mass_inserts(
    CONTACT_FORM_TABLE,
    array('name','email','active'),
    $emails
    );
  
  $conf['ContactForm']['cf_must_initialize'] = false;
  conf_update_param('ContactForm', serialize($conf['ContactForm']));
}


/**
 * Send comment to subscribers
 */
function send_contact_form(&$comm, $key)
{
  global $conf, $page, $template, $conf_mail;
  
  if (!isset($conf_mail))
  {
    $conf_mail = get_mail_configuration();
  }
  
  $query = '
SELECT DISTINCT group_name
  FROM '. CONTACT_FORM_TABLE .'
  ORDER BY group_name
;';
  $groups = array_from_query($query, 'group_name');
  
  $comm = array_merge($comm,
    array(
      'ip' => $_SERVER['REMOTE_ADDR'],
      'agent' => $_SERVER['HTTP_USER_AGENT']
    )
   );

  $comment_action='validate';
  
  // check key
  if (!verify_ephemeral_key(@$key))
  {
    $comment_action='reject';
  }

  // check author
  if ( $conf['ContactForm']['cf_mandatory_name'] and empty($comm['author']) )
  {
    array_push($page['errors'], l10n('Please enter a name'));
    $comment_action='reject';
  }
  
  // check email
  if ( $conf['ContactForm']['cf_mandatory_mail'] and empty($comm['email']) )
  {
    array_push($page['errors'], l10n('Please enter an e-mail'));
    $comment_action='reject';
  }
  else if ( !empty($comm['email']) and !check_email_validity($comm['email']) )
  {
    array_push($page['errors'], l10n('mail address must be like xxx@yyy.eee (example : jack@altern.org)'));
    $comment_action='reject';
  }

  // check subject
  if (empty($comm['subject']))
  {
    array_push($page['errors'], l10n('Please enter a subject'));
    $comment_action='reject';
  }
  else if (strlen($comm['subject']) > 100)
  {
    array_push($page['errors'], sprintf(l10n('%s must not be more than %d characters long'), l10n('Subject'), 100));
    $comment_action='reject';
  }
  
  // check group
  if ( count($groups) > 1 and $comm['group'] == -1 )
  {
    $comm['group'] = true;
    array_push($page['errors'], l10n('Please choose a category'));
    $comment_action='reject';
  }
  
  // check content
  if (empty($comm['content']))
  {
    array_push($page['errors'], l10n('Please enter a message'));
    $comment_action='reject';
  }
  else if (strlen($comm['subject']) > 2000)
  {
    array_push($page['errors'], sprintf(l10n('%s must not be more than %d characters long'), l10n('Message'), 2000));
    $comment_action='reject';
  }
  
  include_once(PHPWG_ROOT_PATH.'include/functions_comment.inc.php');
  include_once(PHPWG_ROOT_PATH.'include/functions_mail.inc.php');
  
  add_event_handler('contact_form_check', 'user_comment_check',
    EVENT_HANDLER_PRIORITY_NEUTRAL, 2);
  
  // perform more spam check
  $comment_action = trigger_event('contact_form_check', $comment_action, $comm);
  
  // get admin emails
  $emails = get_contact_emails($comm['group']);
  if (!count($emails))
  {
    array_push($page['errors'], l10n('Error while sending e-mail'));
    $comment_action='reject';
  }

  if ($comment_action == 'validate')
  {
    // format subject
    $prefix = str_replace('%gallery_title%', $conf['gallery_title'], $conf['ContactForm']['cf_subject_prefix']);
    $subject = '['.$prefix.'] '.$comm['subject'];
    $subject = trim(preg_replace('#[\n\r]+#s', '', $subject));
    $subject = encode_mime_header($subject);
    
    // format content
    $comm['content'] = trigger_event('render_contact_content', $comm['content']);
    if ($conf['ContactForm']['cf_mail_type'] == 'text/html')
    {
      $comm['content'] = nl2br($comm['content']);
    }
    
    // format expeditor
    if (empty($comm['email']))
    {
      $args['from'] = $conf_mail['formated_email_webmaster'];
    }
    else
    {
      $args['from'] = format_email($comm['author'], $comm['email']);
    }
    
    // hearders
    $headers = 'From: '.$args['from']."\n";  
    $headers.= 'MIME-Version: 1.0'."\n";
    $headers.= 'X-Mailer: Piwigo Mailer'."\n";
    $headers.= 'Content-Transfer-Encoding: 8bit'."\n";
    $headers.= 'Content-Type: '.$conf['ContactForm']['cf_mail_type'].'; charset="'.get_pwg_charset().'";'."\n";
    
    set_make_full_url();
    switch_lang_to(get_default_language());
    load_language('plugin.lang', CONTACT_FORM_PATH);
    
    // template
    if ($conf['ContactForm']['cf_mail_type'] == 'text/html')
    {
      $mail_css = file_get_contents(dirname(__FILE__).'/../template/mail/style-'.$conf['ContactForm']['cf_theme'].'.css');
      $template->set_filename('contact_mail', dirname(__FILE__).'/../template/mail/content_html.tpl');
    }
    else
    {
      $mail_css = null;
      $template->set_filename('contact_mail', dirname(__FILE__).'/../template/mail/content_plain.tpl');
    }
    
    $comm['show_ip'] = isset($conf['contact_form_show_ip']) ? $conf['contact_form_show_ip'] : false;
    
    $template->assign(array(
      'cf_prefix' => $prefix,
      'contact' => $comm,
      'GALLERY_URL' => get_gallery_home_url(),
      'PHPWG_URL' => PHPWG_URL,
      'CONTACT_MAIL_CSS' => $mail_css,
      ));
    
    // mail content
    $content = $template->parse('contact_mail', true);
    $content = wordwrap($content, 70, "\n", true);
    
    // send mail
    $result =
      trigger_event('send_mail',
        false, /* Result */
        trigger_event('send_mail_to', implode(',', $emails)),
        trigger_event('send_mail_subject', $subject),
        trigger_event('send_mail_content', $content),
        trigger_event('send_mail_headers', $headers),
        $args
      );
      
    if ( $comm['send_copy'] and !empty($comm['email']) )
    {
      trigger_event('send_mail',
        false, /* Result */
        trigger_event('send_mail_to', $args['from']),
        trigger_event('send_mail_subject', $subject),
        trigger_event('send_mail_content', $content),
        trigger_event('send_mail_headers', $headers),
        $args
      );
    }
    
    unset_make_full_url();
    switch_lang_back();
    load_language('plugin.lang', CONTACT_FORM_PATH);
    
    if ($result == false)
    {
      array_push($page['errors'], l10n('Error while sending e-mail'));
      $comment_action='reject';
    }
  }
  
  return $comment_action;
}

/**
 * get contact emails
 * @param mixed group:
 *    - bool true:    all emails
 *    - empty string: emails without group
 *    - string:       emails with the specified group
 */
function get_contact_emails($group=true)
{
  global $conf;
  
  include_once(PHPWG_ROOT_PATH.'include/functions_mail.inc.php');
  
  $where = '1=1';
  if ($group!==true)
  {
    if (empty($group))
    {
      $where = 'group_name IS NULL';
    }
    else
    {
      $where = 'group_name="'.$group.'"';
    }
  }
  
  $query = '
SELECT *
  FROM '. CONTACT_FORM_TABLE .'
  WHERE 
    '.$where.'
    AND active = "true"
  ORDER BY name ASC
';
  $result = pwg_query($query);
  
  $emails = array();
  while ($data = pwg_db_fetch_assoc($result))
  {
    array_push($emails, format_email($data['name'], $data['email']));
  }
  
  return $emails;
}


/**
 * check if email is valid 
 */
function check_email_validity($mail_address)
{
  if (function_exists('email_check_format'))
  {
    return email_check_format($mail_address); // Piwigo 2.5
  }
  else if (version_compare(PHP_VERSION, '5.2.0') >= 0)
  {
    return filter_var($mail_address, FILTER_VALIDATE_EMAIL)!==false;
  }
  else
  {
    $atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // before  arobase
    $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // domain name
    $regex = '/^' . $atom . '+' . '(\.' . $atom . '+)*' . '@' . '(' . $domain . '{1,63}\.)+' . $domain . '{2,63}$/i';

    return (bool)preg_match($regex, $mail_address);
  }
}

?>