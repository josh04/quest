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
            </tr>";
        return $cron_row;
    }

   /**
    * Cron page
    *
    * @param string $cron_html cron_rows
    * @return string html
    */
    public function cron_page_wrap($cron_html) {
        $cron_page_wrap = "<table>
            <tbody>
                <tr>
                    <th>Function</th>
                    <th>Last triggered</th>
                    <th>Period</th>
                    <th>Enabled</th>
                </tr>
                ".$cron_html."
            </tbody></table>";
        return $cron_page_wrap;
    }
}
?>
