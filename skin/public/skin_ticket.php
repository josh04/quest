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
        $ticket_row = "Submitted on ".$ticket['date'].": <br /><br /><div class='ticket'>".$ticket['message']."</div>".$ticket['sorted']."<hr />";
        return $ticket_row;
    }

   /**
    * ticket wrapper
    *
    * @param string $tickets_html tickets
    */
    public function ticket_wrap($tickets_html) {
        $ticket_wrap = "
                        <p>Welcome to the ticket system. Here you can submit any questions you may have
                        about the game. These will be read the the administrators. You can also use
                        this form to submit any bugs you may find.</p>
                        <p><form action='index.php?page=ticket&amp;action=ticket_submit' method='POST'>
                        Message:<br />
                        <textarea rows='5' cols='80' name='message'>Enter Message Here</textarea>
                        <input type='submit' value='Submit Ticket' />
                        </form>
                        ".$tickets_html;
        return $ticket_wrap;
    }
}
?>
