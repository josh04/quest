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

        return $code_mail;
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

        $this->core('bbcode');

        $mail['time'] = date("F j, Y, g:i a", $mail['time']);
        $mail['quote'] = trim($this->skin->make_quote($mail['username'], $mail['body']));
        $mail['body'] = $this->bbcode->parse($mail['body'], true);
        $mail['subject'] = nl2br($mail['subject']);

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
            $mail_html .= $this->skin->mail_row($mail);
        }

        if ($mail_query->numrows()==0) {
            $mail_html = $this->skin->empty_inbox();
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

        if(isset($_POST['preview'])) {
            $this->core('bbcode');
            $_POST['mail_preview'] = $this->bbcode->parse($_POST['mail_body'], true);
            $compose_submit = $this->compose($this->skin->mail_preview($_POST['mail_preview']));
            return $compose_submit;
        }

        $to = new code_player;

        if (!$to->get_player($_POST['mail_to'])) {
            $compose_submit = $this->compose($this->skin->error_box($this->lang->player_not_found));
            return $compose_submit;
        }

        if (!$_POST['mail_body'] || !$_POST['mail_subject']) {
            $compose_submit = $this->compose($this->skin->error_box($this->lang->fill_in_fields));
            return $compose_submit;
        }

        $mail_check_query = $this->db->execute("SELECT `time` FROM `mail` WHERE `from`=? ORDER BY `time` DESC", array($this->player->id));

        if ($mail_check = $mail_check_query->fetchrow()) {
            if ((time() - $mail_check['time']) < 120) {
                $compose_submit = $this->compose($this->skin->error_box($this->lang->mail_send_too_soon));
                return $compose_submit;
            }
        }
        
        $this->core('mail_api');
        $this->mail_api->send($to->id, $this->player->id, $_POST['mail_body'], $_POST['mail_subject']);

        $compose_submit = $this->mail_inbox($this->lang->mail_sent);
        
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
        $this->core('mail_api');
        $this->mail_api->delete($_POST['mail_id']);
        $delete_multiple_confirm = $this->mail_inbox($this->lang->messages_deleted);
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
        $this->core('mail_api');

        $this->mail_api->mark($_POST['mail-ids'], $read);
        if ($read) {
            $marked = "marked_as_read";
        } else {
            $marked = "marked_as_unread";
        }
        $mark_as_multiple = $this->mail_inbox($this->lang->$marked);
        
        return $mark_as_multiple;
    }

   /**
    * a switcher to perform actions to multiple posts
    *
    * @return string html
    */
    protected function multiple() {

        if (empty($_POST['mail_id'])) {
            $multiple = $this->mail_inbox($this->lang->no_messages_selected);
            return $multiple;
        }

        if ($_POST['multiple_action'] == "mark_as_unread") {
            $multiple = $this->mark_as_multiple( 0 );
        }

        if ($_POST['multiple_action'] == "mark_as_read") {
            $multiple = $this->mark_as_multiple( 1 );
        }

        if ($_POST['multiple_action'] == "delete_multiple") {
            $multiple = $this->delete_multiple();
        }

        if (!isset($multiple)) {
            $multiple = $this->mail_inbox();
        }

        return $multiple;
    }

}
?>
