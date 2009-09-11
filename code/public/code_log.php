<?php
/**
 * battle log
 *
 * @author josh04
 * @package code_public
 */
class code_log extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct($code_other = "") {
        if ($code_other) {
             parent::construct($code_other);
             return;
        }  
        $this->initiate("skin_log");

        $code_log = $this->make_log();

        parent::construct($code_log);
    }

   /**
    * makes log page
    *
    * @return string html
    */
    public function make_log() {
        if ($_POST['action'] == "Clear log") {
            $make_log = $this->clear_log();
            return $make_log;
        }


        //Get all log messages ordered by status
        $log_query = $this->db->execute("SELECT message, status FROM user_log WHERE player_id=? ORDER BY time DESC", array($this->player->id));

        if ($log_query->recordcount() > 0) {
            while ($log = $log_query->fetchrow()) {
                if ($log['status']) {
                    $logs .= $this->skin->log_read($log['message']);
                } else {
                    $logs .= $this->skin->log_unread($log['message']);
                }
            }
            $make_log = $this->skin->make_log($logs);
        } else {
            $make_log = $this->skin->error_box($this->lang->no_laptop_message);
            return $make_log;
        }

        //Update the status of the messages because now they have been read
        $log_update_query = $this->db->Execute('UPDATE `user_log` SET `status`=1 WHERE `player_id`=?',array($this->player->id));
        return $make_log;
    }

   /**
    * clears the log
    *
    * @return string html
    */
    public function clear_log() {
        //Clear all log messages for current user
        $log_delete_query = $this->db->execute("DELETE FROM user_log WHERE player_id=?", array($this->player->id));
        print $this->db->ErrorMsg();
        $_POST['action'] = "";
        $clear_log = $this->make_log();
        return $clear_log;
    }

}
?>
