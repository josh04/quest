<?php
/**
 * Description of code_ticket
 * (TODO) add time to ticket
 *
 * @author grego
 * @package code_admin
 */
class code_ticket extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_ticket");

        $code_ticket = $this->ticket_switch();

        parent::construct($code_ticket);
    }

    public function ticket_switch() {
        if ($_GET['id']) {
            $ticket_switch = $this->single_ticket(intval($_GET['id']));
        } else {
            $ticket_switch = $this->browse_tickets();
        }
        return $ticket_switch;
    }

    public function browse_tickets($message = "") {

        $ticket_query = $this->db->execute("SELECT `tickets`.*,`players`.`username` FROM `tickets` LEFT JOIN `players` ON `tickets`.`player_id`=`players`.`id` ORDER BY `date` DESC");
	if ($ticket_query->numrows()==0) {
            $message = $this->skin->error_box($this->skin->lang_error->no_ticket);
        }
	while($ticket = $ticket_query->fetchrow()) {
            $ticket['message_short'] = substr($ticket['message'],0,30);
            if (strlen($ticket['message']) > 30) {
                $ticket['message_short'] .= "...";
            }
            $ticket_html .= $this->skin->ticket_row($ticket);
        }

        $ticket_display = $this->skin->browse_tickets($ticket_html, $message);
        return $ticket_display;
    }

    public function single_ticket($id) {

        if (isset($_GET['status']) && is_numeric($_GET['status'])) {
            if($_GET['status'] <= 4 && $_GET['status'] >= 0) {
                    $this->db->execute("UPDATE `tickets` SET `status`=? WHERE `id`=?",array(intval($_GET['status']),intval($_GET['id'])));
                    $message .= $this->skin->success_box($this->skin->lang_error->ticket_status_updated);
            }
        }

        if (isset($_POST['ticket-mail'])) {
            $to = new code_player();
            if (!$to->get_player($_POST['to'])) {
                $message .= $this->skin->error_box($this->skin->lang_error->player_not_found);
            } else if (!$_POST['body'] || !$_POST['subject']) {
                $message .= $this->skin->error_box($this->skin->lang_error->fill_in_fields);
            } else {
                require_once("code/public/code_mail.php");
                $code_mail = new code_mail();
                //$code_mail->
                $code_mail->mail_send($_POST['to'], $this->player->id, $_POST['body'], $_POST['subject']);
                $message .= $this->skin->success_box($this->skin->lang_error->mail_sent);
            }
        }

        $ticket_query = $this->db->execute("SELECT `tickets`.*, `players`.`username` FROM `tickets` LEFT JOIN `players` ON `tickets`.`player_id`=`players`.`id` WHERE `tickets`.`id`=?",array(intval($id)));
        
        if ($ticket_query->numrows() == 0) {
            return $this->skin->error_page($this->skin->lang_error->ticket_not_exist);
        }
        $ticket = $ticket_query->fetchrow();
        $status_array[$ticket['status']] = " selected=\"selected\"";
        $ticket_display = $this->skin->single_ticket($ticket, "Uncategorized", $status_array, $message);
        return $ticket_display;
    }
}
?>
