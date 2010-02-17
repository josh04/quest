<?php
/**
 * rpg stats module
 *
 * @author josh04
 * @package code_common
 */
class core_rpg_stats {

   /**
    * updates stat spend
    *
    * @param core_player $player the player!
    * @param string $stat which stat?
    * @return bool success
    */
    public function spend(&$given_player, $stat) {

        if (!$given_player->do_rpg) { // because we no longer necessarily have the rpg data
            /*require_once("code/player/code_player_rpg.php");
            $player = new code_player_rpg();*/ // outdated >.>
            $player = $common->core("player");
            $player->do_rpg = true;

            if (!$player->get_player($common->player->id)) {
                return false;
            }
        } else {
            $player =& $given_player;
        }

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
