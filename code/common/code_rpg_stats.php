<?php
/**
 * rpg stats module
 *
 * @author josh04
 * @package code_common
 */
class code_rpg_stats {

   /**
    * updates stat spend
    *
    * @param code_player $player the player!
    * @param string $stat which stat?
    * @return bool success
    */
    public function spend(&$player, $stat) {
        $stats = array("strength", "vitality", "agility");
        if (in_array($stat, $stats)) {
            $player->stat_points--;
            $player->$stat++;
            $player->update_player(true);
            return true;
        }

        return false;
    }
}
?>
