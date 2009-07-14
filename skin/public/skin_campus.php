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
    public function campus($username, $unread_log, $campus_caption, $campus_welcome) {
        $campus = "
        <h2>".$campus_caption."</h2>
        ".$campus_welcome."

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
