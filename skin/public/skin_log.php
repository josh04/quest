<?php
/**
 * Description of skin_log
 *
 * @author josh04
 * @package skin_public
 */
class skin_log extends skin_common {

   /**
    * makes log page
    *
    * @param string $logs log rows
    * @return string html
    */
    public function make_log($logs) {
        $make_log = "<h2>Log</h2><form action='index.php?section=public&amp;page=laptop' method='POST'><input type='submit' name='action' value='Clear log' />".$logs;
        return $make_log;
    }

   /**
    * read log row
    *
    * @param string $log log message
    * @return string html
    */
    public function log_read($log) {
        $log_read = "<fieldset><legend>Read</legend>".$log."</fieldset>";
        return $log_read;
    }

   /**
    * unread log row
    *
    * @param string $log log message
    * @return string html
    */
    public function log_unread($log) {
        $log_unread = "<fieldset><legend>Unread</legend>".$log."</fieldset>";
        return $log_unread;
    }
}
?>
