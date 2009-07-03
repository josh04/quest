<?php
  //function mail_single_message($message)
  $message = $a[0];

  		$this->html .= "
      <a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose'>New Message</a>
      <table width='100%' border='0'>

			<tr><td width='20%'><b>To:</b></td><td width='80%'><a href='profile.php?id=" . $message['to'] . "'>" . $player->username . "</a></td></tr>
			<tr><td width='20%'><b>From:</b></td><td width='80%'><a href='profile.php?id=" . $message['from'] . "'>" . $message['fromusername'] . "</a></td></tr>
			<tr><td width='20%'><b>Date:</b></td><td width='80%'>" . date("F j, Y, g:i a", $message['time']) . "</td></tr>
			<tr><td width='20%'><b>Subject:</b></td><td width='80%'>" . $message['subject'] . "</td></tr>
			<tr><td width='20%'><b>Body:</b></td><td width='80%'>" . nl2br($message['body']) . "</td></tr>
			</table>
			<br /><br />
			<table width='30%'>
			<tr><td width='100%'>
			<form method='get' action='' style='display:inline;'>
			<input type='hidden' name='page' value='mail' />
			<input type='hidden' name='action' value='compose' />
			<input type='hidden' name='to' value='" . $message['fromusername'] . "' />
			<input type='submit' name='reply' value='Reply' />
			</form>
			<form method='post' action='index.php?page=mail&amp;action=delete' style='display:inline;'>
			<input type='hidden' name='id' value='" . $message['id'] . "' />
			<input type='submit' name='delete' value='Delete' />
			</form>
			</td></tr></table>";
    	$this->final_output();
?>
