<?php
  
  //function ticket_page($tickets)
  $tickets = $a[0];
    while($row = $tickets->fetchrow($result)) {
	   $ticketshtml .= "Submitted on "
        . date("Y-m-d H:i:s", $row['date'])
        . ": <br /><br /><div class='ticket'>" . $row['msg']."</div>";
	   switch($row['sorted']){
      case 0:
        $ticketshtml .= "<br /><i>This ticket has not been read yet.</i>";
        break;
      case 1:
        $ticketshtml .= "<br />This ticket has been read.";
        break;
      case 2:
        $ticketshtml .= "<br />This ticket has been responded to.";
        break;
      }
      $ticketshtml .= "<hr />";
    }

$this->html .= "
<p>Welcome to the ticket system. Here you can submit any questions you may have 
about the game. These will be read the the administrators. You can also use 
this form to submit any bugs you may find.</p>
<p><form action='index.php?page=ticket' method='post'>
Message:<br />
<textarea rows='5' cols='80' name='msg'>Enter Message Here</textarea>
<input type='submit' value='Submit Ticket' />
</form>
".$ticketshtml."
";
    $this->final_output();

?>
