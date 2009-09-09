<?php
/**
 * skin_stats.class.php
 *
 * Level up page skin
 *
 * @author josh04
 * @package code_public
 */
class skin_stats extends skin_common {

   /**
    * we increased a stat woo
    *
    * @param string $stat_name which stat?
    * @param int $stat_value how many they got now?
    * @return string html
    */
    public function stat_increased($stat_name, $stat_value) {
        $stat_increased = "<div class='success'>You have increased your ".$stat_name.". It is now at ".$stat_value.".</div>
        <a href='index.php'>Back home</a>";
        return $stat_increased;
    }


   /**
    * returns the link if you have stats points to spend
    *
    * @param integer $stat_points number of available stat points
    * @return string html
    */
    public function stats_link($stat_points) {
        $stats_link = "<div class='success'>You have ".$stat_points." stat points to spend.</div>";
        return $stats_link;
    }

   /**
    * table 'o' stats
    *
    * @param int $strength player strength
    * @param int $vitality player vitality
    * @param int $agility player agility
    * @return string html
    */
    public function stats_table($strength, $vitality, $agility) {
        $stats_table = "
            <table style=\"width:100px;margin:8px auto;\">
                <tr><th style=\"width: 100px;\">Strength</th><td>".$strength."</td></tr>
                <tr><th>Vitality</th><td>".$vitality."</td></tr>
                <tr><th>Agility</th><td>".$agility."</td></tr>
            </table>";
        return $stats_table;
    }

   /**
    * table 'o' stats, and buttons to press
    *
    * @param int $strength player strength
    * @param int $vitality player vitality
    * @param int $agility player agility
    * @return string html
    */
    public function stats_table_can_spend($strength, $vitality, $agility) {
        $stats_table = "<form action='index.php?page=stats' method='POST'>
            <table style=\"width:100px;margin:8px auto;\">
                <tr><th style=\"width: 100px;\">Strength</th><td><div style='margin:100% 0px;'>".$strength."</div></td>
                    <td>
                        <form action='index.php?page=stats' method='POST'>
                            <input type='hidden' name='stat' value='strength' />
                            <input type='submit' value='+' />
                        </form></td></tr>
                <tr><th>Vitality</th><td><div style='margin:100% 0px;'>".$vitality."</div></td>
                    <td>
                        <form action='index.php?page=stats' method='POST'>
                            <input type='hidden' name='stat' value='vitality' />
                            <input type='submit' value='+' />
                        </form></td></tr>
                <tr><th>Agility</th><td><div style='margin:100% 0px;'>".$agility."</div></td>
                    <td>
                        <form action='index.php?page=stats' method='POST'>
                            <input type='hidden' name='stat' value='agility' />
                            <input type='submit' value='+' />
                        </form></td></tr>
            </table></form>";
        return $stats_table;
    }
}
?>
