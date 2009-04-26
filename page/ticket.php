<?php
// ticket.php : So the bastards can complain.
// Does the sending of tickets and the reviewing of own tickets.

if ($_POST['msg']){
  define("PAGENAME", "Ticket Submitted");

  $query = $db->execute("INSERT INTO tickets (player_id, msg, date) 
                        VALUES (?,?,?)", 
                        array(intval($player->id), 
                                     htmlentities($_POST['msg'],ENT_COMPAT,'UTF-8'), 
                                     time()));

  ($query) ? "" : print "Error.";
  $display->simple_page("Your ticket has been submitted. You will receive a response via the message system.");
  exit;
}

$tickets = $db->execute("SELECT * FROM tickets 
                        WHERE player_id=? 
                        ORDER BY date DESC", 
                        array($player->id));
($tickets) ? print "" : print "Error Selecting Your Tickets.";

$display->ticket($tickets);

?>
