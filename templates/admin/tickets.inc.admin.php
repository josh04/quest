<?php

  //function admin_tickets($tickets)
  $tickets = $a[0];
    while($ticket = $tickets->fetchrow()) {
    $this->html .= "Submitted by <a href='index.php?page=profile&amp;id=" . $ticket['player_id'] . "'>" . $ticket['username'] 
          . "</a> - <a href=\"index.php?page=mail&amp;action=compose&to=" 
          . $ticket['username'] . "\">[Mail]</a> on "
          . date("Y-m-d H:i:s", $ticket['date'])
          . ": <br /><br /><div class='ticket'>" . $ticket['msg']."</div>";
    switch($ticket['sorted']){
      case 0:
        $this->html .= "<br /><i>This ticket has not been read yet.</i>";
        break;
      case 1:
        $this->html .= "<br />This ticket has been read.";
        break;
      case 2:
        $this->html .= "<br />This ticket has been responded to.";
        break;
    }
    $this->html .= "<br /><a href=\"admin.php?page=ticket&amp;sorted=0&amp;id=".$ticket['id']."\"/>[Unread]</a> - 
       <a href=\"admin.php?page=ticket&amp;sorted=1&amp;id=".$ticket['id']."\"/>[Read]</a> - 
       <a href=\"admin.php?page=ticket&amp;sorted=2&amp;id=".$ticket['id']."\"/>[Responded]</a><br />";
    $this->html .= "<br /><hr />";
    }
    $this->final_output();
  
?>
