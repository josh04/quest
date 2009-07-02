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
    * displays you-have-stats screen
    *
    * @param integer $stats_points number of player stats points
    * @param string $stats_message message if updated
    * @return string html
    */
    public function stats_to_spend($stat_points, $stats_message) {
        $stats_to_spend = "
            <h2>Stats Centre</h2>
            ".$stats_message."

            <b>Stat Training Centre:</b><br />
            <i>You have ".$stat_points."
            stat points to spend, agent. What would you
            like to spend them on?</i>
            <br /><br />
            <form action='index.php?page=stats&amp;action=spend' method='POST' />
            <input type='submit' name='stat' value='Strength' /><br />
            <input type='submit' name='stat' value='Vitality' /><br />
            <input type='submit' name='stat' value='Agility' /><br />
            ";
        return $stats_to_spend;
    }

   /**
    * displays you-have-stats screen
    * 
    * @param string $stats_message message if updated
    * @return string html
    */
    public function stats_none($stats_message) {
        $stats_none = "
            <h2>Stats Centre</h2>
            <b>Stat Training Centre:</b><br />
            <i>Sorry agent, but you currently do not have any
            stat points to spend.<br />
            Please come back later when you have leveled up.</i>
            ";
        return $stats_none;
    }
}
?>
