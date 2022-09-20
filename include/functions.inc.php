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
    $page['section'] = 'contact';
    $page['title'] = l10n('Contact');
    $page['body_id'] = 'theContactPage';
    $page['is_external'] = true;
    $page['is_homepage'] = false;

    $page['section_title'] = 
      '<a href="'.get_absolute_root_url().'">'.l10n('Home').'</a>'
      .$conf['level_separator']
      .'<a href="'.CONTACT_FORM_PUBLIC.'">'.l10n('Contact').'</a>';
  }
}

/**
 * contact page
 */
function contact_form_page()
{
  global $page;

  if (isset($page['section']) && $page['section'] == 'contact')
  {
    include(CONTACT_FORM_PATH . 'include/contact_form.inc.php');
  }
}

/**
 * public menu link
 */
function contact_form_applymenu($menu_ref_arr)
{
  global $conf;

  if (!$conf['ContactForm_ready'] || (
    !is_classic_user() && !$conf['ContactForm']['cf_allow_guest']
  ))
  {
    return;
  }

  $menu = &$menu_ref_arr[0];
  if (($block = $menu->get_block('mbMenu')) != null)
  {
    $block->data['contact'] = array(
      'URL' => CONTACT_FORM_PUBLIC,
      'NAME' => l10n('Contact'),
      );
  }
}

/**
 * change contact link in footer
 */
function contact_form_footer_link($content)
{
  $search = '#<a href="mailto:{\\$CONTACT_MAIL}\\?subject={\'A comment on your site\'\\|@?translate\\|@?escape:url}">#';
  $replace = '<a href="{$CONTACT_FORM_PUBLIC}">';
  return preg_replace($search, $replace, $content);
}

/**
 * change contact link in mail footer
 */
function contact_form_mail_template($cache_key, $content_type)
{
  global $conf_mail;

  $template = &$conf_mail[$cache_key]['theme'];
  $template->assign('CONTACT_FORM_PUBLIC', CONTACT_FORM_PUBLIC);

  if ($content_type == 'text/html')
  {
    $template->set_prefilter('mail_footer', 'contact_form_mail_template_html');
  }
  else
  {
    $template->set_prefilter('mail_footer', 'contact_form_mail_template_plain');
  }
}
function contact_form_mail_template_html($content)
{
  return str_replace(
    'mailto:{$CONTACT_MAIL}?subject={\'A comment on your site\'|translate|escape:url}',
    '{$CONTACT_FORM_PUBLIC}',
    $content
    );
}
function contact_form_mail_template_plain($content)
{
  return str_replace(
    '{$CONTACT_MAIL}',
    '{$CONTACT_FORM_PUBLIC}',
    $content
    );
}

/**
 * init emails list
 */
function contact_form_initialize_emails()
{
  global $conf;

  $query = '
SELECT
    u.'.$conf['user_fields']['id'].' AS id
  FROM '.USERS_TABLE.' AS u
    JOIN '.USER_INFOS_TABLE.' AS i
    ON i.user_id =  u.'.$conf['user_fields']['id'].'
  WHERE i.status IN (\'webmaster\',  \'admin\')
    AND '.$conf['user_fields']['email'].' IS NOT NULL
  ORDER BY username
;';
  $result = pwg_query($query);

  $emails = array();
  while ($row = pwg_db_fetch_assoc($result))
  {
    $emails[] = array(
      'user_id' => $row['id'],
      'name' => null,
      'email' => null,
      );
  }

  mass_inserts(
    CONTACT_FORM_TABLE,
    array('user_id','name','email'),
    $emails
    );

  $conf['ContactForm']['cf_must_initialize'] = false;
  conf_update_param('ContactForm', $conf['ContactForm']);
}


/**
 * Send comment to subscribers
 */
function send_contact_form(&$comm, $key)
{
  global $conf, $page, $template;

  $comm = array_merge($comm,
    array(
      'ip' => $_SERVER['REMOTE_ADDR'],
      'agent' => $_SERVER['HTTP_USER_AGENT'],
      'show_ip' => isset($conf['contact_form_show_ip']) && $conf['contact_form_show_ip'],
    )
   );

  $comment_action='validate';

  // check key
  if (!verify_ephemeral_key(@$key))
  {
    $comment_action='reject';
  }

  // check author
  if ($conf['ContactForm']['cf_mandatory_name'] and empty($comm['author']))
  {
    $page['errors'][] = l10n('Please enter a name');
    $comment_action='reject';
  }

  // check email
  if ($conf['ContactForm']['cf_mandatory_mail'] and empty($comm['email']))
  {
    $page['errors'][] = l10n('Please enter an e-mail');
    $comment_action='reject';
  }
  else if (!empty($comm['email']) and !email_check_format($comm['email']))
  {
    $page['errors'][] = l10n('mail address must be like xxx@yyy.eee (example : jack@altern.org)');
    $comment_action='reject';
  }

  // check subject
  if (empty($comm['subject']))
  {
    $page['errors'][] = l10n('Please enter a subject');
    $comment_action='reject';
  }
  else if (strlen($comm['subject']) > 100)
  {
    $page['errors'][] = sprintf(l10n('%s must not be more than %d characters long'), l10n('Subject'), 100);
    $comment_action='reject';
  }

  // check content
  if (empty($comm['content']))
  {
    $page['errors'][] = l10n('Please enter a message');
    $comment_action='reject';
  }
  else if (strlen($comm['subject']) > 2000)
  {
    $page['errors'][] = sprintf(l10n('%s must not be more than %d characters long'), l10n('Message'), 2000);
    $comment_action='reject';
  }

  include_once(PHPWG_ROOT_PATH.'include/functions_comment.inc.php');
  include_once(PHPWG_ROOT_PATH.'include/functions_mail.inc.php');

  add_event_handler('contact_form_check', 'user_comment_check',
    EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

  // perform more spam check
  $comment_action = trigger_change('contact_form_check', $comment_action, $comm);

  // get admin emails
  $to = get_contact_emails();
  if (!count($to))
  {
    $page['errors'][] = l10n('Error while sending e-mail');
    $comment_action='reject';
  }

  if ($comment_action == 'validate')
  {
    $comm['content'] = trigger_change('render_contact_content', $comm['content']);

    $prefix = str_replace('%gallery_title%', $conf['gallery_title'], $conf['ContactForm']['cf_subject_prefix']);

    $from = $Cc = $Bcc = null;
    if (!empty($comm['email']))
    {
      $from = array(
        'name' => $comm['author'],
        'email' => $comm['email'],
        );
    }

    switch_lang_to(get_default_language());
    load_language('plugin.lang', CONTACT_FORM_PATH);

    $result = pwg_mail(
      $to,
      array(
        'subject' => '['.$prefix.'] '.$comm['subject'],
        'content' => $comm['content'],
        'mail_title' => $prefix,
        'mail_subtitle' => $comm['subject'],
        'content_format' => 'text/html',
        'email_format' => $conf['ContactForm']['cf_mail_type'],
        'from' => $from,
        'Cc' => $Cc,
        'Bcc' => $Bcc,
        ),
      array(
        'filename' => 'mail',
        'dirname' => realpath(CONTACT_FORM_PATH . 'template'),
        'assign' => array(
          'CONTACT' => $comm,
          ),
        )
      );

    switch_lang_back();
    load_language('plugin.lang', CONTACT_FORM_PATH);

    if ($result == false)
    {
      $page['errors'][] = l10n('Error while sending e-mail');
      $comment_action='reject';
    }
  }

  return $comment_action;
}

/**
 * get contact emails
 */
function get_contact_emails()
{
  global $conf;

  $query = '
SELECT
    cf.name AS cf_name,
    cf.email AS cf_email,
    u.'.$conf['user_fields']['username'].' AS u_name,
    u.'.$conf['user_fields']['email'].' AS u_email
  FROM '. CONTACT_FORM_TABLE .' AS cf
  LEFT JOIN '. USERS_TABLE .' AS u
    ON cf.user_id = u.'.$conf['user_fields']['id'].'
';
  $result = query2array($query);
  
  $emails = array();
  foreach ($result as $row)
  {
    if (!empty($row['u_email']))
    {
      $emails[] = array(
        'name' => $row['u_name'],
        'email' => $row['u_email'],
        );
    }
    else if (!empty($row['cf_email']))
    {
      $emails[] = array(
        'name' => $row['cf_name'],
        'email' => $row['cf_email'],
        );
    }
  }

  return $emails;
}

/**
 * delete contact entry when the user is deleted
 */
function contact_form_delete_user($user_id)
{
  pwg_query('DELETE FROM '. CONTACT_FORM_TABLE .' WHERE user_id = '. $user_id .';');
}

/**
 * register AJAX methods
 */
function contact_form_ws_add_methods($arr)
{
  $service = &$arr[0];
  
  $service->addMethod(
    'pwg.cf.addUser',
    'contact_form_ws_add_user',
    array('user_id' => array('type' => WS_TYPE_ID)),
    null, null,
    array('admin_only'=>true, 'hidden'=>true)
    );
  
  $service->addMethod(
    'pwg.cf.addEmail',
    'contact_form_ws_add_email',
    array(
      'name' => array(),
      'email' => array(),
      ),
    null, null,
    array('admin_only'=>true, 'hidden'=>true)
    );
  
  $service->addMethod(
    'pwg.cf.delete',
    'contact_form_ws_delete',
    array('id' => array('type' => WS_TYPE_ID)),
    null, null,
    array('admin_only'=>true, 'hidden'=>true)
    );
}

/**
 * AJAX: add user
 */
function contact_form_ws_add_user($params)
{
  global $conf;
  
  $result = pwg_query('SELECT id FROM '.CONTACT_FORM_TABLE.' WHERE user_id = '.$params['user_id'].';');
  
  if (pwg_db_num_rows($result))
  {
    return new PwgError(WS_ERR_INVALID_PARAM, l10n('User already added'));
  }
  
  $result = pwg_query('SELECT id FROM '.USERS_TABLE.' WHERE '.$conf['user_fields']['id'].' = '.$params['user_id'].' AND '.$conf['user_fields']['email'].' IS NOT NULL;');
  
  if (!pwg_db_num_rows($result))
  {
    return new PwgError(WS_ERR_INVALID_PARAM, l10n('This user has no e-mail'));
  }
  
  single_insert(CONTACT_FORM_TABLE, array(
    'user_id' => $params['user_id'],
    'name' => null,
    'email' => null,
    ));
  
  $query = '
SELECT
    cf.id AS id,
    u.'.$conf['user_fields']['username'].' AS name,
    u.'.$conf['user_fields']['email'].' AS email
  FROM '. CONTACT_FORM_TABLE .' AS cf
  LEFT JOIN '. USERS_TABLE .' AS u
    ON cf.user_id = u.'.$conf['user_fields']['id'].'
  WHERE cf.user_id = '.$params['user_id'].'
';
  $result = query2array($query);
  
  return $result[0];
}

/**
 * AJAX: add email
 */
function contact_form_ws_add_email($params)
{
  if (!email_check_format($params['email']))
  {
    return new PwgError(WS_ERR_INVALID_PARAM, l10n('mail address must be like xxx@yyy.eee (example : jack@altern.org)'));
  }
  
  $params = array_map('pwg_db_real_escape_string', $params);
  
  $result = pwg_query('SELECT id FROM '.CONTACT_FORM_TABLE.' WHERE email = \''.$params['email'].'\';');
  
  if (pwg_db_num_rows($result))
  {
    return new PwgError(WS_ERR_INVALID_PARAM, l10n('E-mail already added'));
  }
  
  single_insert(CONTACT_FORM_TABLE, array(
    'user_id' => null,
    'name' => $params['name'],
    'email' => $params['email'],
    ));
  
  return array(
    'id' => pwg_db_insert_id(),
    'name' => $params['name'],
    'email' => $params['email'],
    );
}

/**
 * AJAX: delete entry
 */
function contact_form_ws_delete($params)
{
  pwg_query('DELETE FROM '.CONTACT_FORM_TABLE.' WHERE id = '.$params['id'].';');
}
