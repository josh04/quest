<?php
// laptop.php : Shows battle results.
// Clears logs, shows logs. Pretty pointless.

if ($_GET['action'] == "clear")
{
	//Clear all log messages for current user
	$clear = $db->execute("DELETE FROM user_log WHERE player_id=?", array($player->id));
	($clear) ? "" : print "Error deleting logs.";
}

//Get all log messages ordered by status
$logs = $db->execute("SELECT msg, status FROM user_log 
                       WHERE player_id=? ORDER BY time DESC", 
                       array($player->id));
($logs) ? "" : print "Error retrieving logs.";



if ($logs->recordcount() > 0)
{
  $display->laptop($logs);
}
else
{
	$display->simple_page("No laptop messages.","","byellow");
}

//Update the status of the messages because now they have been read
$status = $db->execute("UPDATE user_log SET status=1 
                       WHERE player_id=? AND status=0", 
                       array($player->id));
($status) ? "" : print "Error updating status.";

?>
