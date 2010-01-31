<?php
/**
 * for adding log entries
 *
 * @author josh04
 */
class core_log {

   /**
    * initiation function
    *
    * @param code_common $common page calling
    */
    public function load_core($common) {
        $this->db =& code_database_wrapper::get_db();
    }

   /**
    * notify the player
    *
    * @param string $message what to say
    * @return bool success
    */
    public function add($id, $message) {
        if ($id == 0) {
            return false;
        }
        $insert_log['player_id'] = $id;
        $insert_log['message'] = $message;
        $insert_log['time'] = time();
        $log_query = $this->db->AutoExecute('user_log', $insert_log, 'INSERT');

        if ($log_query) {
            return true;
        } else {
            return false;
        }
    }

}
?>
