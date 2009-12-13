<?php
/**
 * skin_stats.class.php
 *
 * Level up page skin
 *
 * @author josh04
 * @package skin_public
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
    * @param int $stat_points number of available stat points
    * @param int $strength player strength
    * @param int $vitality player vitality
    * @param int $agility player agility
    * @return string html
    */
    public function stats_table_can_spend($stat_points, $strength, $vitality, $agility) {
        $stats_table = "<div class='success'>You have ".$stat_points." stat points to spend.</div>
            <form action='index.php?section=public&amp;page=stats' method='POST'>
            <table style=\"width:100px;margin:8px auto;\">
                <tr><th style=\"width: 100px;\">Strength</th><td><div style='margin:100% 0px;'>".$strength."</div></td>
                    <td>
                        <form action='index.php?section=public&amp;page=index&amp;action=spend' method='POST'>
                            <input type='hidden' name='stat' value='strength' />
                            <input type='submit' value='+' />
                        </form></td></tr>
                <tr><th>Vitality</th><td><div style='margin:100% 0px;'>".$vitality."</div></td>
                    <td>
                        <form action='index.php?section=public&amp;page=index&amp;action=spend' method='POST'>
                            <input type='hidden' name='stat' value='vitality' />
                            <input type='submit' value='+' />
                        </form></td></tr>
                <tr><th>Agility</th><td><div style='margin:100% 0px;'>".$agility."</div></td>
                    <td>
                        <form action='index.php?section=public&amp;page=index&amp;action=spend' method='POST'>
                            <input type='hidden' name='stat' value='agility' />
                            <input type='submit' value='+' />
                        </form></td></tr>
            </table></form>";
        return $stats_table;
    }

   /**
    * This function is a troll
    *
    * @param string $stats stats table
    * @param int $level player level
    * @param int $exp player xp
    * @param int $exp_max player next level xp
    * @param int $hp player hp
    * @param int $hp_max player total hp
    * @param int $energy player energy
    * @param int $energy_max max player energy
    * @param string $registered date player registered
    * @param string $last_active date player was last on
    * @param string $to_spend message if there is some to spend
    * @param string $message error message
    * @return string html
    */
    public function common_table($stats, $level, $exp, $exp_max, $hp, $hp_max,
            $energy, $energy_max, $registered, $last_active, $message = "") {
        $common_table = $message."<table><tr><td style=\"width:50%;padding:8px;\">
            <strong>Level:</strong> ".$level."<br />
            <strong>XP:</strong> ".$exp." (".($exp_max-$exp)." to next level)<br />
            <strong>HP:</strong> ".$hp." (".$hp_percent."%)<br />
            <strong>Energy:</strong> ".$energy."/".$energy_max."<br />
            <strong>Registered:</strong> ".$registered."<br />
            <strong>Last active:</strong> ".$last_active."
            </td><td style=\"width:50%;\">
            ".$stats."
            </td></tr></table>";
        return $common_table;
    }
}
?>
