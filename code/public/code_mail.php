<?php
/**
 * Player mailbox. only battle left!
 *
 * @author josh04
 * @package code_public
 */
class code_mail extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_mail");

        $code_mail = $this->mail_switch();

        parent::construct($code_mail);
    }

   /**
    * where to?
    *
    * @return string html
    */
    public function mail_switch() {
        switch($_GET['action']) {
            case 'compose':
                $mail_switch = $this->compose();
                return $mail_switch;
            case 'compose_submit':
                $mail_switch = $this->compose_submit();
                return $mail_switch;
            case 'delete_multiple':
                $mail_switch = $this->delete_multiple();
                return $mail_switch;
            case 'delete_multiple_confirm':
                $mail_switch = $this->delete_multiple_confirm();
                return $mail_switch;
            case 'read':
                $mail_switch = $this->read();
                return $mail_switch;
        }

        $mail_switch = $this->mail_inbox();
        return $mail_switch;
    }

   /**
    * displays a message; if unread, makes read
    *
    * @return string html
    */
    public function read() {

        $mail_query = $this->db->execute("SELECT m.*, p.username FROM mail AS m LEFT JOIN players AS p ON m.from=p.id WHERE m.id=? AND m.to=?",
                                array(intval($_GET['id']), $this->player->id));
        $mail = $mail_query->fetchrow();

        if (!$mail['status']) {
            $mail['status'] = 1;
            $mail_update['status'] = 1;
            $status_query = $this->db->AutoExecute('mail', $mail_update, 'UPDATE', "id=".$mail['id']);

        }

        $mail['time'] = date("F j, Y, g:i a", $mail['time']);
        $mail['body'] = nl2br($mail['body']);
        $mail['subject'] = nl2br($mail['subject']);
        $read = $this->skin->read($mail, $this->player->username);
        return $read;
    }

   /**
    * list of player mails
    * (TODO) Pagination
    *
    * @return string html
    */
    public function mail_inbox($message = "") {
        $mail_query = $this->db->execute("SELECT m.*, p.username FROM mail AS m LEFT JOIN players AS p ON m.from=p.id WHERE m.to=? ORDER BY m.time DESC",
                                array($this->player->id));
        while($mail = $mail_query->fetchrow()) {
            $mail['time'] = date("F j, Y, g:i a", $mail['time']);
            $mail_html .= $this->skin->mail_row($mail);
        }
        $mail_list = $this->skin->mail_wrap($mail_html, $message);
        return $mail_list;
    }

   /**
    * make a mail form
    *
    * @param string $message error message
    * @return string html
    */
    public function compose($message = "") {
        if ($_POST['mail_to']) {
            $to = htmlentities($_POST['mail_to'],ENT_COMPAT,'UTF-8');
        } else {
            $to = htmlentities($_GET['to'],ENT_COMPAT,'UTF-8');
        }
        $subject = htmlentities($_POST['mail_subject'],ENT_COMPAT,'UTF-8');
        $body = htmlentities($_POST['mail_body'],ENT_COMPAT,'UTF-8');
        $compose = $this->skin->compose($to, $subject, $body, $message);
        return $compose;
    }

   /**
    * sends the mail
    *
    * @return string html
    */
    public function compose_submit() {
        //Process mail info, show success message
        $to = new code_player;
        $to->db =& $this->db;
        if (!$to->get_player_by_name($_POST['mail_to'])) {
            $compose_submit = $this->compose("User does not exist");
            return $compose_submit;
        }

        if (!$_POST['mail_body'] || !$_POST['mail_subject']) {
            $compose_submit = $this->compose("You must fill in all fields.");
            return $compose_submit;
        }


        $mail_insert['to'] = intval($to->id);
        $mail_insert['from'] = intval($this->player->id);
        $mail_insert['body'] = htmlentities($_POST['mail_body'],ENT_COMPAT,'UTF-8');
        $mail_insert['subject'] = htmlentities($_POST['mail_subject'],ENT_COMPAT,'UTF-8');
        $mail_insert['time'] = time();
        $mail_insert['status'] = 0;

        $compose_query = $this->db->AutoExecute('mail', $mail_insert, 'INSERT');
        print $this->db->ErrorMsg();
        $compose_submit = $this->mail_inbox("Mail sent.");
        
        return $compose_submit;

    }

   /**
    * delete sum mails
    *
    * @return string html
    */
    public function delete_multiple() {
        foreach ($_POST['mail_id'] as $mail_id) {
            $delete_html .= $this->skin->mail_delete_input(intval($mail_id));
        }

        $delete_multiple = $this->skin->delete_multiple($delete_html);
        return $delete_multiple;
    }

   /**
    * really deletes sum mails
    *
    * @return string html
    */
    public function delete_multiple_confirm() {
        foreach ($_POST['mail_id'] as $mail_id) {
            $mail_ids .= intval($mail_id).", ";
        }

        $mail_ids = "(".substr($mail_ids, 0, strlen($mail_ids)-2).")";

        $this->db->execute('DELETE FROM mail WHERE id IN '.$mail_ids.' AND `to`=?', array(intval($this->player->id))); // eh, this is sloppy.
        $delete_multiple_confirm = $this->mail_inbox("Messages deleted.");
        return $delete_multiple_confirm;
    }


}
?>
