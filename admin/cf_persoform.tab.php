<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

check_status(ACCESS_ADMINISTRATOR);

global $template, $conf, $user;

if (isset($_POST['submitpersoform']))
{
  conf_update_param('persoformtop', $_POST['persoform_top']);
  conf_update_param('persoformbottom', $_POST['persoform_bottom']);

  // reload configuration
  load_conf_from_db();
}

//charge Persoform
$template->assign(
  array(
    'PERSOFORMTOP' => $conf['persoformtop'],
    'PERSOFORMBOTTOM' => $conf['persoformbottom'],
    )
  );
?>