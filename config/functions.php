<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

//Gets the number of unread messages
function unread_messages($id, &$db)
{
	$query = $db->getone("select count(*) as `count` from `mail` where `to`=? and `status`='unread'", array($id));
	return $query['count'];
}

//Gets new log messages
function unread_log($id, &$db)
{
	$query = $db->getone("select count(*) as `count` from `user_log` where `player_id`=? and `status`='unread'", array($id));
	return $query['count'];
}

//Insert a log message into the user logs
function addlog($id, $msg, &$db)
{
	$insert['player_id'] = $id;
	$insert['msg'] = $msg;
	$insert['time'] = time();
	$query = $db->autoexecute('user_log', $insert, 'INSERT');
	print $db->ErrorMsg();
}

//ADDED BY JAMIE - Checks the URL of the page
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

include("core/awards.php");

?>
