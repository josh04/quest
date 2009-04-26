<?php
  //function laptop_page($messages) 
  //Laptop Page
  $messages = $a[0];
   $this->html = "<a href='index.php?page=laptop&amp;action=clear'>Clear log</a>";
	 while ($message = $messages->fetchrow()) {
	   if ($message['status'] == 1) {
	     $read .= "<fieldset><legend>Read</legend>".$message['msg']."</fieldset>";
     } else {
     	 $unread .= "<fieldset><legend>Unread</legend>".$message['msg']."</fieldset>";
     }
	 }
	 $this->html .= $read.$unread;
	 $this->final_output();
?>
