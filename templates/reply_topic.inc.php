<?php

  //function reply_topic()
		$this->html .= "
          <a href='index.php?page=forums'>Forum Home</a> | <a href='index.php?page=forums&action=newtopic'>New Topic</a> 
    <form method='POST' action='index.php?page=forums&amp;action=reply'>
		<table width='100%' border='0'>
		<input type='hidden' name='topic_id' value=".$_GET['topic_id']."/>
		<tr><td width='20%'><b>Content:</b></td><td width='80%'><textarea name='content' rows='15' cols='50'></textarea></td></tr>
		<tr><td></td><td><input type='submit' value='Send' name='replytopic' /></td></tr>
		</table>
		</form>";
    $this->final_output();

?>
