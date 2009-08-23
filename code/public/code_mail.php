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
    * Send Mail API
    *
    * @access public
    * @param int $to Receiving player id
    * @param int $from Sending player id
    * @param string $body Body of message - no html
    * @param string $subject Subject of message - definitely no html
    * @return int Mail database id
    */
    public function mail_send($to, $from, $body, $subject) {
        $mail_insert['to'] = intval($to);
        $mail_insert['from'] = intval($from);
        $mail_insert['body'] = htmlentities($body,ENT_QUOTES,'UTF-8');
        $mail_insert['subject'] = htmlentities($subject,ENT_QUOTES,'UTF-8');
        $mail_insert['time'] = time();
        $mail_insert['status'] = 0; // unread

        $compose_query = $this->db->AutoExecute('mail', $mail_insert, 'INSERT');
        if ($this->db->ErrorMsg()) {
            $this->error_page($this->skin->lang_error->db_query_failed);
        }
        return $this->db->Insert_Id();
    }

   /**
    * where to?
    *
    * @return string html
    */
    protected function mail_switch() {
        switch($_GET['action']) {
            case 'compose':
                $mail_switch = $this->compose();
                return $mail_switch;
            case 'compose_submit':
                $mail_switch = $this->compose_submit();
                return $mail_switch;
            case 'multiple':
                $mail_switch = $this->multiple();
                return $mail_switch;
            case 'delete_multiple':
                $mail_switch = $this->delete_multiple();
                return $mail_switch;
            case 'delete_multiple_confirm':
                $mail_switch = $this->delete_multiple_confirm();
                return $mail_switch;
            case 'mark_as_unread':
                $mail_switch = $this->mark_as_multiple( 0 );
                return $mail_switch;
            case 'mark_as_read':
                $mail_switch = $this->mark_as_multiple( 1 );
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
    protected function read() {

        $mail_query = $this->db->execute("SELECT m.*, p.username FROM mail AS m LEFT JOIN players AS p ON m.from=p.id WHERE m.id=? AND m.to=?",
                                array(intval($_GET['id']), $this->player->id));
        $mail = $mail_query->fetchrow();

        if (!$mail['status']) {
            $mail['status'] = 1;
            $mail_update['status'] = 1;
            $status_query = $this->db->AutoExecute('mail', $mail_update, 'UPDATE', "id=".$mail['id']);

        }

        $mail['time'] = date("F j, Y, g:i a", $mail['time']);
        $mail['body'] = $this->bbparse(nl2br(stripslashes($mail['body'])));
        $mail['subject'] = nl2br(stripslashes($mail['subject']));

        if (substr($mail['subject'],0,3) != "RE:") {
            $mail['pre'] = "RE: ";
        }
        $read = $this->skin->read($mail, $this->player->username);
        return $read;
    }

   /**
    * list of player mails
    * (TODO) Pagination
    *
    * @return string html
    */
    protected function mail_inbox($message = "") {
        $mail_query = $this->db->execute("SELECT m.*, p.username FROM mail AS m LEFT JOIN players AS p ON m.from=p.id WHERE m.to=? ORDER BY m.time DESC",
                                array($this->player->id));
        while($mail = $mail_query->fetchrow()) {
            $mail['time'] = date("F j, Y, g:i a", $mail['time']);
            $mail['subject'] = stripslashes($mail['subject']);
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
    protected function compose($message = "") {
        if ($_POST['mail_to']) {
            $to = htmlentities($_POST['mail_to'],ENT_QUOTES,'UTF-8');
        } else {
            $to = htmlentities($_GET['to'],ENT_QUOTES,'UTF-8');
        }

        if ($_POST['mail_subject']) {
            $subject = htmlentities($_POST['mail_subject'],ENT_QUOTES,'UTF-8');
        } else {
            $subject = htmlentities($_GET['subject'],ENT_QUOTES,'UTF-8');
        }
        $body = htmlentities($_POST['mail_body'],ENT_QUOTES,'UTF-8');
        $compose = $this->skin->compose($to, $subject, $body, $message);
        return $compose;
    }

   /**
    * sends the mail
    *
    * @return string html
    */
    protected function compose_submit() {
        //Process mail info, show success message
        $to = new code_player;

        if (!$to->get_player($_POST['mail_to'])) {
            $compose_submit = $this->compose($this->skin->error_box($this->skin->lang_error->player_not_found));
            return $compose_submit;
        }

        if (!$_POST['mail_body'] || !$_POST['mail_subject']) {
            $compose_submit = $this->compose($this->skin->error_box($this->skin->lang_error->fill_in_fields));
            return $compose_submit;
        }

        $mail_check_query = $this->db->execute("SELECT `time` FROM `mail` WHERE `from`=? ORDER BY `time` DESC", array($this->player->id));

        if ($mail_check = $mail_check_query->fetchrow()) {
            if ((time() - $mail_check['time']) < 120) {
                $compose_submit = $this->compose($this->skin->error_box($this->skin->lang_error->mail_send_too_soon));
                return $compose_submit;
            }
        }
        

        $this->mail_send($to->id, $this->player->id, $_POST['mail_body'], $_POST['mail_subject']);

        $compose_submit = $this->mail_inbox($this->skin->lang_error->mail_sent);
        
        return $compose_submit;

    }

   /**
    * delete sum mails
    *
    * @return string html
    */
    protected function delete_multiple() {
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
    protected function delete_multiple_confirm() {
        foreach ($_POST['mail_id'] as $mail_id) {
            $mail_ids .= intval($mail_id).", ";
        }

        $mail_ids = "(".substr($mail_ids, 0, strlen($mail_ids)-2).")";

        $this->db->execute('DELETE FROM mail WHERE id IN '.$mail_ids.' AND `to`=?', array(intval($this->player->id))); // eh, this is sloppy.
        $delete_multiple_confirm = $this->mail_inbox($this->skin->lang_error->messages_deleted);
        return $delete_multiple_confirm;
    }
    
   /**
    * static menu function
    *
    * @param code_menu $menu the current menu object, allows adding of top/bottom and db etc
    * @param string $label The text label
    * @return string html
    */
    public static function code_mail_menu(&$menu, $label) {

        $menu->player->unread = 0;

        $mail_query = $menu->db->execute(" SELECT `m`.*, `p`.`username` FROM `mail` AS `m`
                                        LEFT JOIN `players` AS `p` ON `m`.`from`=`p`.`id`
                                        WHERE `m`.`to`=? AND `m`.`status`=0
                                        ORDER BY `m`.`time` DESC", array($menu->player->id));


        if ($mail_query->RecordCount()) {
            require_once("skin/public/skin_mail.php");
            while ($mail = $mail_query->fetchrow()) {
                $mail['subject'] = stripslashes($mail['subject']);
                $mail_rows .= skin_mail::mail_row_small($mail['id'], $mail['username'], $mail['subject']);
                $menu->player->unread++;
            }
            
            $mail = skin_mail::mail_wrap_small($mail_rows);
            $menu->top .= $mail;

        }
        $menu_entry_addition = $label." [".$menu->player->unread."]";
        return $menu_entry_addition;
    }

   /**
    * mark a collection of mail items as read or unread
    *
    * @param boolean $read whether to mark the messages as read (true) or unread (false)
    * @return string html
    */
    protected function mark_as_multiple($read) {
        foreach ($_POST['mail_id'] as $mail_id) {
            $mail_ids .= intval($mail_id).", ";
        }

        $mail_ids = "(".substr($mail_ids, 0, strlen($mail_ids)-2).")";

        $this->db->Execute("UPDATE `mail` SET `status`=? WHERE `id` IN ".$mail_ids, array($read));

        if($read) $marked = "marked_as_read";
        else $marked = "marked_as_unread";
        $mark_as_multiple = $this->mail_inbox($this->skin->lang_error->$marked);
        
        return $mark_as_multiple;
    }

   /**
    * a switcher to perform actions to multiple posts
    *
    * @return string html
    */
    protected function multiple() {

        if(empty($_POST['mail_id'])) {
            $multiple = $this->mail_inbox( $this->skin->lang_error->no_messages_selected );
            return $multiple;
        }

        if($_POST['multiple_action']=="mark_as_unread")
            $multiple = $this->mark_as_multiple( 0 );

        if($_POST['multiple_action']=="mark_as_read")
            $multiple = $this->mark_as_multiple( 1 );

        if($_POST['multiple_action']=="delete_multiple")
            $multiple = $this->delete_multiple();

        if(!isset($multiple))
            $multiple = $this->mail_inbox();

        return $multiple;
    }

}
?>
