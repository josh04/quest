<?php
/**
 * just a stub, really.
 *
 * @author josh04
 * @package skin_public
 */
class skin_portal extends skin_common {
    
   /**
    * main portal page
    * 
    * @param string $username current player name
    * @param integer $unread_log number of unread logs
    * @param string $portal_caption name of portal section
    * @param string $portal_welcome welcome text on page
    * @return string html
    */
    public function portal($username, $unread_log, $portal_caption, $portal_welcome, $portal_links) {
        $portal = "
        <h2>".$portal_caption."</h2>
        ".$portal_welcome."

        <div class='gbox'><table><tr><td>
        ".$portal_links."
        </td></tr></table></div>";
        return $portal;
    }

}
?>
