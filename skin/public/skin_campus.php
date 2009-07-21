<?php
/**
 * just a stub, really.
 * (TODO) simpler way of static pages?
 *
 *
 * @author josh04
 * @package skin_public
 */
class skin_campus extends skin_common {
    
   /**
    * main portal page
    * 
    * @param string $username current player name
    * @param integer $unread_log number of unread logs
    * @param string $campus_caption name of campus section
    * @param string $campus_welcome welcome text on page
    * @return string html
    */
    public function campus($username, $unread_log, $campus_caption, $campus_welcome, $portal_links) {
        $campus = "
        <h2>".$campus_caption."</h2>
        ".$campus_welcome."

        <div class='gbox'><table><tr><td>
        ".$portal_links."
        </td></tr></table></div>";
        return $campus;
    }

}
?>
