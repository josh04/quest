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
    * @return string html
    */
    public function campus($username, $unread_log) {
        $campus = "
        <h2>Campus</h2>
        <strong>Security Guard:</strong><br />
        <i>Welcome onto Campus, agent. Let me just check your ID.<br />
        ...<br />
        You have permission to enter, ".$username.". If you follow the path to the left, you can get to the Main Building. To the right you'll find the training areas and accommodation. Have a good day.</i>

        <div class=\"gbox\">
        <table><tr>

        <td><strong>Main Building</strong><br />
        <a href='index.php?page=hospital'>Hospital</a><br />
        <a href='index.php?page=bank'>Bank</a><br />
        <a href='index.php?page=store'>Store</a><br />
        <a href='index.php?page=work'>Work</a>
        </td>

        <td style='width:50%;'><strong>Your Room</strong><br />
        <a href='index.php?page=laptop'>Laptop [".$unread_log."]</a><br />
        <a href='index.php?page=inventory'>Inventory</a><br /><br />

        <strong>Dojo</strong><br />
        <a href='index.php?page=battle'>Combat Training</a>
        </td>

        </tr></table></div>";
        return $campus;
    }

}
?>
