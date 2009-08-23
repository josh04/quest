<?php
/**
 * Description of skin_cron_admin
 *
 * @author josh04
 * @package skin_admin
 */
class skin_cron_admin extends skin_common {
   /**
    * one for each cron function
    *
    * @param array cron db entry
    * @return string html
    */
    public function cron_row($cron) {
        $cron_row = "<tr>
                <td>".$cron['function']."</td>
                <td>".$cron['last_active']."</td>
                <td>".$cron['hours']." hours<br />".$cron['minutes']." minutes<br />".$cron['seconds']." seconds</td>
                <td>".$cron['enabled']."</td>
                <td><a href='index.php?section=admin&amp;page=cron&amp;action=edit&amp;id=".$cron['id']."'><img src='images/icons/pencil.png' alt='edit' /></a></td>
            </tr>";
        return $cron_row;
    }

   /**
    * Cron page
    *
    * @param string $cron_html cron_rows
    * @param string $message error message
    * @return string html
    */
    public function cron_page_wrap($cron_html, $message="") {
        $cron_page_wrap = "<h2>Cron editor</h2>
            ".$message."
            <p>From here, you can edit the scheduled cron tasks.</p>
            <table><tbody>
                <tr>
                    <th>Function</th>
                    <th>Last triggered</th>
                    <th>Period</th>
                    <th>Enabled</th>
                    <th>&nbsp;</th>
                </tr>
                ".$cron_html."
            </tbody></table>";
        return $cron_page_wrap;
    }
}
?>
