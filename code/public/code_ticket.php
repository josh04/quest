<?php
/**
 * Description of code_ticket
 * (TODO) OH SHIT THE SPAM POTENTIAL
 *
 * @author josh04
 * @package code_public
 */
class code_ticket extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct_page() {
        $this->initiate("skin_ticket");

        $code_ticket = $this->ticket_switch();

        parent::construct_page($code_ticket);
    }

   /**
    * post, or just view?
    *
    * @return string html
    */
    public function ticket_switch() {
        if ($_GET['action'] == 'ticket_submit') {
            $ticket_switch = $this->ticket_submit();
            return $ticket_switch;
        }

        $ticket_switch = $this->ticket_list();
        return $ticket_switch;
    }

   /**
    * shows list of your tickets
    *
    * @return string html
    */
    public function ticket_list() {
        $ticket_query = $this->db->execute("SELECT * FROM tickets WHERE player_id=? ORDER BY date DESC", array($this->player->id));

        while($ticket = $ticket_query->fetchrow()) {
            switch($ticket['sorted']){
                case 0:
                    $ticket['sorted'] = "<br /><i>This ticket has not been read yet.</i>";
                    break;
                case 1:
                    $ticket['sorted'] = "<br />This ticket has been read.";
                    break;
                case 2:
                    $ticket['sorted'] = "<br />This ticket has been responded to.";
                    break;
            }
            $ticket['date'] = date("Y-m-d H:i:s", $ticket['date']);
            $tickets_html .= $this->skin->ticket_row($ticket);
        }

        $ticket_list = $this->skin->ticket_wrap($tickets_html);
        return $ticket_list;
    }

   /**
    * adds a new ticket
    *
    * @return string html
    */
    public function ticket_submit() {
        $insert_ticket['player_id'] = $this->player->id;
        $insert_ticket['message'] = htmlentities($_POST['message'],ENT_COMPAT,'UTF-8');
        $insert_ticket['date'] = time();

        $ticket_insert_query = $this->db->AutoExecute('tickets', $insert_ticket, 'INSERT');

        $ticket_submit = $this->ticket_list();
        return $ticket_submit;
    }

}
?>
