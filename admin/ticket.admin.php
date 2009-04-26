<?php
// ticket.admin.php : Admin tickets.

// Sort tickets.
if(isset($_GET['id']) && (abs($_GET['sorted']) <= 2)) {
  $sort = $db->execute("UPDATE tickets 
                         SET sorted=? 
                         WHERE id=?", 
                         array(abs(intval($_GET['sorted'])), 
                               intval($_GET['id']) ) );
  ($sort) ? "" : print "Error updating.";
}

$tickets = $db->execute("SELECT t.*, p.username 
                        FROM tickets AS t 
                        LEFT JOIN players AS p 
                        ON t.player_id=p.id 
                        ORDER BY t.date DESC");
($tickets) ? "" : print "Error retrieving tickets.";

$display->tickets($tickets);

?>