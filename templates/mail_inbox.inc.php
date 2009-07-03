<?php
  
  //function mail_inbox($messages)
  $messages = $a[0];
    if ($messages->recordcount() > 0) {
			$bool = 1;

			while($message = $messages->fetchrow()) {
				$status = (!$message['status']) ? "style='font-weight:bold;'" : "";
				$inbox .= "<tr class='row" . $bool . "' ".$status.">
				<td width='5%'><input type='checkbox' name='id[]' value='".$message['id']."' /></td>
				<td width='20%'>
				<a href='index.php?page=profile&amp;id=".$message['from']."'>".$message['username']."</a>
				</td>
				<td width='35%'>
				<a href='index.php?page=mail&amp;action=read&amp;id=".$message['id']."'>".$message['subject']."</a>
				</td>
				<td width='40%'>".date("F j, Y, g:i a", $message['time'])."</td>
				</tr>";
				$bool = ($bool==1)?2:1;
			}
		} else {
			$inbox = "<tr class=\"row1\">
			<td colspan=\"4\"><b>No Messages</b></td>
			</tr>";
		}
		$this->html = "
          <a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose'>New Message</a>
    <form method='post' action='index.php?page=mail&amp;action=deleteseveral' name='inbox'>
		<table width='100%' border='0'>
		<tr>
		<th width='5%'><input type='checkbox' onclick='javascript: checkAll();' name='check' /></td>
		<th width='20%'><b>From</b></td>
		<th width='35%'><b>Subject</b></td>
		<th width='40%'><b>Date</b></td>
		</tr>
		".$inbox."
		</table>
		<input type='submit' name='delmultiple' value='Delete' />
		</form>";
		$this->final_output();

?>
