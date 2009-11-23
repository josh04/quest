<?php
require_once("code/common/code_questdb.php");
require_once("code/common/code_player.php");
require_once("skin/common/skin_common.php");
require_once("code/common/code_database_wrapper.php");
require_once("config.php");

// Run! Get to the database!
$db = &NewQuestDB();
$db->Connect($config['server'], $config['db_username'], $config['db_password'], $config['database']);

// Check if player is logged in.
$player = new code_player(array(), $config);
$player->make_player();
if(!$player->is_member) die();

// Find the file we want
$query = $db->Execute("SELECT * FROM `help` WHERE `id`=?", array($_GET['id']));
if($query->numrows()==0) {
    $title = "Error";
    $body = "Help page not found";
} else {
    $row = $query->fetchrow();
    $title = $row['title'];
    $body = $row['body'];
}

?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'> 
<html xml:lang='en' lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Help - <?=$title?></title>
<meta http-equiv='content-type' content='text/html; charset=utf-8' />
<link rel='stylesheet' type='text/css' href='./skin/common/default.css' />
</head>
<body>
<div class='help-item-top'><?=$title?></div>
<div class='help-item'><?=$body?></div>
</body>
</html>