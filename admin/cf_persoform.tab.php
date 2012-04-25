<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
check_status(ACCESS_ADMINISTRATOR);

global $template, $conf, $user;

//charge Persoform
$query = '
select param,value
	FROM ' . CONFIG_TABLE . '
  WHERE param = "persoformtop"
	;';
$result = pwg_query($query);

$row = mysql_fetch_array($result);
    
  $template->assign(
    array(
      'PERSOFORMTOP' => $row['value'],
      ));

$query = '
select param,value
	FROM ' . CONFIG_TABLE . '
  WHERE param = "persoformbottom"
	;';
$result = pwg_query($query);

$row = mysql_fetch_array($result);
    
  $template->assign(
    array(
      'PERSOFORMBOTTOM' => $row['value'],
      ));

if (isset($_POST['submitpersoform']))
	{
	$q = 'DELETE FROM ' . CONFIG_TABLE . ' WHERE param="persoformtop" LIMIT 1;';
    pwg_query($q);
		$query = 'INSERT INTO ' . CONFIG_TABLE . ' (param,value,comment) VALUES ("persoformtop","'.$_POST['persoform_top'].'","Add text above form to contactform plugin");';
    pwg_query($query);

  $template->assign(
    array(
      'PERSOFORMTOP' => stripslashes($_POST['persoform_top']),
      ));

	$q = 'DELETE FROM ' . CONFIG_TABLE . ' WHERE param="persoformbottom" LIMIT 1;';
    pwg_query($q);
	$query = 'INSERT INTO ' . CONFIG_TABLE . ' (param,value,comment) VALUES ("persoformbottom","'.$_POST['persoform_bottom'].'","Add text below form to contactform plugin");';
    pwg_query($query);

  $template->assign(
    array(
      'PERSOFORMBOTTOM' => stripslashes($_POST['persoform_bottom']),
      ));
	}	
	
?>