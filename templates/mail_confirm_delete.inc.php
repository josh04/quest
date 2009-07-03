<?php
  //function mail_confirm_delete()
  					$this->html .= "Are you sure you want to delete this message?<br /><br />
						<form method='post' action='index.php?page=mail&amp;action=delete'>
						<input type='hidden' name='id' value='".htmlentities($_POST['id'],ENT_COMPAT,'UTF-8')."' />
						<input type='hidden' name='confirm' value='1' />
						<input type='submit' name='delete' value='Delete!' />
						</form>";
		$this->final_output();
?>
