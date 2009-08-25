<?php
/**
 * Description of skin_ticket
 *
 * @author josh04
 * @package skin_public
 */
class skin_ticket extends skin_common {
    
   /**
    * single ticket row
    *
    * @param array $ticket 
    */
    public function ticket_row($ticket) {
        $ticket_row = "Submitted on <em>".$ticket['date']."</em>\n<div class='ticket'>".$ticket['message']."</div><br /><br />";
        return $ticket_row;
    }

   /**
    * ticket wrapper
    *
    * @param string $tickets_html tickets
    * @param int $tickets number of tickets
    * @param string $plural text issue
    * @param string $ticket_draft to display in the text box
    * @param string $message error what
    * @return string html
    */
    public function ticket_wrap($tickets_html, $tickets, $plural, $ticket_draft, $message="") {
        $ticket_wrap = $message."

                        <h2>Ticket Control</h2>
                        <p>Welcome to the ticket system. Here you can submit any questions you may have
                        about the game. These will be read the the administrators. You can also use
                        this form to submit any bugs you may find.</p>
                        <p><form action='index.php?page=ticket&amp;action=ticket_submit' method='POST'>
                        <textarea rows='5' cols='80' name='message'>".$ticket_draft."</textarea>
                        <input type='submit' value='Submit Ticket' />
                        </form></p>
                        <h3>Your tickets</h3><p>You currently have <strong>".$tickets."</strong> open ticket".$plural."</p>
                        ".$tickets_html;
        return $ticket_wrap;
    }

}
?>
