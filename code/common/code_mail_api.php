<?php
/**
 * mail sending api
 *
 * @author josh04
 * @package code_common
 */
class code_mail_api {
    
   /**
    * initiation function
    *
    * @param code_common $common page calling
    * @return code_mail_api this thing
    */
    public function load_core($common) {
        $this->db =& code_database_wrapper::get_db();
        return $this;
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
    public function send($to, $from, $body, $subject) {
        $mail_insert['to'] = intval($to);
        $mail_insert['from'] = intval($from);
        $mail_insert['body'] = htmlentities($body,ENT_QUOTES,'UTF-8');
        $mail_insert['subject'] = htmlentities($subject,ENT_QUOTES,'UTF-8');
        $mail_insert['time'] = time();
        $mail_insert['status'] = 0; // unread

        $compose_query = $this->db->AutoExecute('mail', $mail_insert, 'INSERT');
        if ($this->db->ErrorMsg()) {
            return false;
        }
        return $this->db->Insert_Id();
    }

   /**
    * deletes one or many mails
    *
    * @param array $ids array of or single int id
    * @return bool success
    */
    public function delete($ids) {
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $mail_ids .= intval($mail_id).", ";
            }
        } else {
            $mail_ids = "(".intval($ids).")";
        }

        $mail_ids = "(".substr($mail_ids, 0, strlen($mail_ids)-2).")";

        $this->db->execute('DELETE FROM mail WHERE id IN '.$mail_ids.' AND `to`=?', array(intval($this->player->id))); // eh, this is sloppy.

        if ($this->db->ErrorMsg()) {
            return false;
        }

        return true;
    }

   /**
    * marks mail as read/unread
    *
    * @param array $ids mail ids to mark
    * @param bool $as true for read, false for unread
    * @return bool success
    */
    public function mark($ids, $as) {
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $mail_ids .= intval($mail_id).", ";
            }
        } else {
            $mail_ids = "(".intval($ids).")";
        }

        $mail_ids = "(".substr($mail_ids, 0, strlen($mail_ids)-2).")";

        if ($as) {
            $read = 1;
        } else {
            $read = 0;
        }

        $this->db->Execute("UPDATE `mail` SET `status`=? WHERE `id` IN ".$mail_ids, array($read));
        
        if ($this->db->ErrorMsg()) {
            return false;
        }

        return true;
    }

}
?>
