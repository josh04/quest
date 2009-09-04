<?php
/**
 * Description of code_ticket
 * (TODO) OH SHIT THE SPAM POTENTIAL
 *
 * @author josh04
 * @package code_public
 */
class ticket_mod extends code_ticket {

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
        return "This page modded".$ticket_switch;
    }

   /**
    * shows list of your tickets
    *
    * @param string $message error what
    * @return string html
    */
    public function ticket_list($message='') {
        $ticket_query = $this->db->execute("SELECT * FROM tickets WHERE player_id=? AND status=0 ORDER BY date DESC", array($this->player->id));

        while($ticket = $ticket_query->fetchrow()) {
            $ticket['date'] = date("Y-m-d H:i:s", $ticket['date']);
            $tickets_html .= $this->skin->ticket_row($ticket);
        }

        if ($_POST['message']) {
            $ticket_draft = htmlentities($_POST['message'], ENT_QUOTES, 'utf-8');
        } else {
            $ticket_draft = "Enter message here.";
        }

        if ($ticket_query->numrows() > 1) {
            $plural = "s. They are shown below.";
        } else if ($ticket_query->numrows()==0) {
            $plural = "s.";
        } else {
            $plural = ". It is shown below.";
        }

        $ticket_list = $this->skin->ticket_wrap($ticket_header, $ticket_query->numrows(), $plural, $ticket_draft, $message);
        return $ticket_list.$tickets_html;
    }

   /**
    * adds a new ticket
    *
    * @return string html
    */
    public function ticket_submit() {
	if ($_POST['message']=="" || !isset($_POST['message'])) {
            return $this->ticket_list($this->skin->error_box($this->lang->no_ticket_entered));
        }

        $ticket_check_query = $this->db->execute("SELECT `date` FROM `tickets` WHERE `player_id`=? ORDER BY `date` DESC", array($this->player->id));

        if ($ticket_check_query->RecordCount() >= 20) {
            $ticket_submit = $this->ticket_list($this->skin->error_box($this->lang->too_many_tickets));
            return $ticket_submit;
        }

        if ($ticket_check = $ticket_check_query->fetchrow()) {
            if ((time() - $ticket_check['date']) < 120) {
                $ticket_submit = $this->ticket_list($this->skin->error_box($this->lang->ticket_send_too_soon));
                return $ticket_submit;
            }
        }

        $insert_ticket['player_id'] = $this->player->id;
        $insert_ticket['message'] = htmlentities($_POST['message'],ENT_QUOTES,'UTF-8');
        $insert_ticket['date'] = time();

        $ticket_insert_query = $this->db->AutoExecute('tickets', $insert_ticket, 'INSERT');

        $ticket_submit = $this->ticket_list($this->skin->success_box($this->lang->ticket_submitted));
        return $ticket_submit;
    }

}
?>
