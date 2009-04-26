<?php
// mail.php : argh mail. hate this.

if ($_POST['sendmail']) {
	//Process mail info, show success message
	$to = new player;
	$to->db =& $db;
	($to->get_user_by_name($_POST['to'])) ? 0 : $display->simple_page("Selected user does not exist.","","bred");

	if (!$_POST['body']) {
		$display->simple_page("You must enter a message!<br />","","bred");
	}

		$body = htmlentities($_POST['body'],ENT_COMPAT,'UTF-8');
		$subject = ($_POST['subject'] == "") ? "No Subject" : htmlentities($_POST['subject'],ENT_COMPAT,'UTF-8');

		$sent = $db->execute("INSERT INTO mail (`to`, `from`, `subject`, `body`, `time`) 
                          VALUES (?,?,?,?,?)", 
                          array($to->id, 
                                $player->id, 
                                $subject,
                                $body,
                                time()));
		$display->simple_page(($sent) ? "The message was sent to ".$to->username."." : "The message failed to send.","","byellow");
}

switch($_GET['action']) {
	case "read": //Reading a message
		$read = $db->execute("SELECT m.*, p.username AS fromusername FROM mail AS m
		                      LEFT JOIN players AS p
		                      ON m.from=p.id
                          WHERE m.id=? AND m.to=?", 
                          array($_GET['id'], $player->id));
		if ($read->recordcount() == 1) {
		  $read = $read->fetchrow();
      $display->mail_single_message($read);
      if (!$read['status']) {
				$status = $db->execute("UPDATE mail
                                SET status=1 
                                WHERE id=?", 
                                array($read['id']));
        ($status) ? 0 : print "Error updating status";
			}
		}
		break;
	
	case "compose": //Composing mail (just the form, processing is at the top of the page)
    $display->mail_compose_message();
		break;
	
	case "delete":
			if (!$_POST['id']) {
				$display->simple_page("A message must be selected.","","bred");
			}	else {
				$query = $db->getone("SELECT count(*) AS count 
                              FROM `mail` 
                              WHERE `id`=? AND `to`=?", 
                              array(intval($_POST['id']), 
                                    $player->id));
				if ($query['count'] == 0) {
					$display->simple_page("This message does not belong to you.","","bred");
				}	else {
					if (!$_POST['confirm'] || $_POST['confirm'])
					{
						$delete = $db->execute("DELETE FROM `mail`
                                    WHERE `id`=?", 
                                    array($_POST['id']));
						$display->simple_page("The message has been successfully deleted.","","byellow");
					}
				}
			}
    break;
    
	case "deleteseveral":
				if (!$_POST['id'])
			{
				$display->simple_page("A message must be selected!","","bred");
			}
			else
			{
				foreach($_POST['id'] as $msg)
				{
					$query = $db->getone("select count(*) as `count` from `mail` where `id`=? and `to`=?", array($msg, $player->id));
					if ($query['count'] = 0)
					{
						//In case there are some funny guys out there ;)
						echo "This message does not belong to you!";
						$delerror = 1;
					}
				}
				if (!$delerror)
				{
					if (!$_POST['deltwo'])
					{
						$meextra="";
						foreach($_POST['id'] as $msg)
						{
							$meextra.="<input type=\"hidden\" name=\"id[]\" value=\"" . $msg . "\" />\n";
						}
						(count($_POST['id'])>1) ? $tm1="these messages" : $tm1="this message";
						$display->html_page("
<div class=\"bcyan\">Are you sure you want to delete ".$tm1."?<br /><br />
<form method=\"post\" action=\"index.php?page=mail&action=deleteseveral\">
" . $meextra . "
<input type=\"hidden\" name=\"deltwo\" value=\"1\" />
<input type=\"submit\" name=\"delmultiple\" value=\"Delete!\" />
</form></div>");
					}
					else
					{
						foreach($_POST['id'] as $msg)
						{
							$query = $db->execute("delete from `mail` where `id`=?", array($msg));
						}
						$display->html_page("<div class=\"byellow\">The message has been successfully deleted!<br /><br /><a href=\"index.php?page=mail\">Return to Inbox</a></div>");
						//Redirect back to inbox, or show success message
						//Can be changed in the admin panel (TODO)
					}
				}
			}
		break;
	
	default: //Show inbox
	
		$messages = $db->execute("SELECT m.*, p.username
                           FROM mail AS m
                           LEFT JOIN players AS p
                           ON m.from=p.id
                           WHERE m.to=? 
                           ORDER BY m.time DESC", 
                           array($player->id));
     $display->mail_inbox($messages);
		break;
}

?>
