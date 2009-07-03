<?php
  //function new_topic()

		$this->html .= "
          <a href='index.php?page=forums'>Forum Home</a> | <a href='index.php?page=forums&action=newtopic'>New Topic</a>
    <form method='POST' action='index.php?page=forums&amp;action=newtopic'>
		<table width='100%' border='0'>
		<tr><td width='20%'><b>Title:</b></td><td width='80%'><input type='text' name='title'/></td></tr>
		<tr><td width='20%'><b>Content:</b></td><td width='80%'><textarea name='content' rows='15' cols='50'></textarea></td></tr>
		<tr><td></td><td><input type='submit' value='Send' name='posttopic' /></td></tr>
		</table>
		</form>";
    $this->final_output();
?>
