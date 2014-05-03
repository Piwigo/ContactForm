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

    $page['section_title'] = '<a href="'.get_absolute_root_url().'">'.l10n('Home').'</a>'.$conf['level_separator'].'<a href="'.CONTACT_FORM_PUBLIC.'">'.l10n('Contact').'</a>';
  }
}

/**
 * contact page
 */
function contact_form_page()
{
  global $page;

  if (isset($page['section']) and $page['section'] == 'contact')
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

  if (!$conf['ContactForm']['cf_menu_link']) return;
  if (!is_classic_user() and !$conf['ContactForm']['cf_allow_guest']) return;
  if (!$conf['ContactForm_ready']) return;

  $menu = &$menu_ref_arr[0];
  if (($block = $menu->get_block('mbMenu')) != null)
  {
    $block->data[] = array(
      'URL' => CONTACT_FORM_PUBLIC,
      'NAME' => l10n('Contact'),
      );
  }
}

/**
 * change contact link in footer
 */
function contact_form_footer_link($content, &$smarty)
{
  $search = '<a href="mailto:{$CONTACT_MAIL}?subject={\'A comment on your site\'|@translate|@escape:url}">';
  $replace = '<a href="{$CONTACT_FORM_PUBLIC}">';
  return str_replace($search, $replace, $content);
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
    u.'.$conf['user_fields']['username'].' AS username,
    u.'.$conf['user_fields']['email'].' AS email
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
      'name' => $row['username'],
      'email' => $row['email'],
      'active' => 'true',
      );
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
  global $conf, $page, $template;

  $query = '
SELECT DISTINCT group_name
  FROM '. CONTACT_FORM_TABLE .'
  ORDER BY group_name
;';
  $groups = array_from_query($query, 'group_name');

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

  // check group
  if (count($groups) > 1 and $comm['group'] == -1)
  {
    $comm['group'] = true;
    $page['errors'][] = l10n('Please choose a category');
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
  $comment_action = trigger_event('contact_form_check', $comment_action, $comm);

  // get admin emails
  $emails = get_contact_emails($comm['group']);
  if (!count($emails))
  {
    $page['errors'][] = l10n('Error while sending e-mail');
    $comment_action='reject';
  }

  if ($comment_action == 'validate')
  {
    $comm['content'] = trigger_event('render_contact_content', $comm['content']);

    $prefix = str_replace('%gallery_title%', $conf['gallery_title'], $conf['ContactForm']['cf_subject_prefix']);

    $from = $Cc = null;
    if (!empty($comm['email']))
    {
      $from = array(
        'name' => $comm['author'],
        'email' => $comm['email'],
        );
      if ($comm['send_copy'])
      {
        $Cc = $from;
      }
    }

    switch_lang_to(get_default_language());
    load_language('plugin.lang', CONTACT_FORM_PATH);

    $result = pwg_mail(
      $emails,
      array(
        'subject' => '['.$prefix.'] '.$comm['subject'],
        'mail_title' => $prefix,
        'mail_subtitle' => $comm['subject'],
        'content_format' => 'text/html',
        'from' => $from,
        'Cc' => $Cc,
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
SELECT name, email
  FROM '. CONTACT_FORM_TABLE .'
  WHERE
    '.$where.'
    AND active = "true"
  ORDER BY name ASC
';
  $emails = array_from_query($query);

  return $emails;
}
