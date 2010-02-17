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
    public function spend(&$player, $stat) {
        $stats = array("strength", "vitality", "agility");
        if (in_array($stat, $stats)) {
            $player->stat_points--;
            $player->$stat++;
            $player->update_player(true); // why not use update_player here?
            /*
            $update_stats['stat_points'] = $player->stat_points;
            $update_stats[$stat] = $player->$stat;
            $player->db->AutoExecute('players', $update_stats, "UPDATE", 'id='.$player->id);
             */
            return true;
        }

        return false;
    }
}
?>
