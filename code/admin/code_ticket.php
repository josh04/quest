<?php
/**
 * Description of code_ticket
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

    public function browse_tickets() {

        $ticketq = $this->db->execute("SELECT `tickets`.*,`players`.`username` FROM `tickets` LEFT JOIN `players` ON `tickets`.`player_id`=`players`.`id` ORDER BY `date` DESC");
	$ticket_html = ($ticketq->numrows()==0) ? "<tr><td colspan='3'><h3>No tickets have been issued</h3></td></tr>" :"";
	while($ticket = $ticketq->fetchrow()) {
                $ticket_html .= $this->skin->ticket_row($ticket);
        }

        $ticket_display = $this->skin->browse_tickets($ticket_html, $message);
        return $ticket_display;
    }

    public function single_ticket($id) {

        if(isset($_GET['status']) && is_numeric($_GET['status'])) {
        if($_GET['status']<=4 && $_GET['status']>=0) {
                $this->db->execute("UPDATE `tickets` SET `status`=? WHERE `id`=?",array($_GET['status'],$_GET['id']));
                $message .= "<div class='success'>Status updated</div>";
                }
        }

        if(isset($_POST['ticket-mail'])) {
        $mailarr = array($_POST['to'],$this->player->id,$_POST['subject'],$_POST['body'],time());
        if($this->db->execute("INSERT INTO `mail` (`to`,`from`,`subject`,`body`,`time`) VALUES (?,?,?,?,?)",$mailarr));
          $message .= "<div class='success'>Mail sent</div>";
        }

        $ticketq = $this->db->execute("SELECT `tickets`.*, `players`.`username` FROM `tickets` LEFT JOIN `players` ON `tickets`.`player_id`=`players`.`id` WHERE `tickets`.`id`=?",array($id));
        if($ticketq->numrows()==0) {return $this->skin->error_page($this->skin->lang_error->ticket_not_exist);}

        $ticket_display = $this->skin->single_ticket($ticketq->fetchrow(), "Uncategorized", $message);
        return $ticket_display;
    }
}
?>
