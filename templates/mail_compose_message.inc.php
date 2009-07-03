<?php
  //function mail_compose_message()

if(isset($_POST['body'])) $body=htmlentities($_POST['body'],ENT_COMPAT,'UTF-8'); else $body="";

		$this->html .= "
          <a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose'>New Message</a>
    <form method='POST' action='index.php?page=mail&amp;action=compose'>
		<table width='100%' border='0'>
		<tr><td width='20%'><b>To:</b></td><td width='80%'><input type='text' name='to' value='".htmlentities($_REQUEST['to'],ENT_COMPAT,'UTF-8')."' /></td></tr>
		<tr><td width='20%'><b>Subject:</b></td><td width='80%'><input type='text' name='subject' value='".htmlentities($_POST['subject'],ENT_COMPAT,'UTF-8')."' /></td></tr>
		<tr><td width='20%'><b>Body:</b></td><td width='80%'>
		<textarea name='body' rows='15' cols='50'>".$body."</textarea>
		</td></tr>
		<tr><td></td><td><input type='submit' value='Send' name='sendmail' AccessKey='S' /></td></tr>
		</table>
		</form>";
    		$this->final_output();
    		
?>
