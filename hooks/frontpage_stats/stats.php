<?php
/**
 * frontpage_stats hook
 * 
 * displays the stats box on the main page
 * @package code_hooks
 * @author josh04
 */

/**
 * stats table function
 *
 * @param code_common $common calling page
 */
function stats_table($common) {
    $common->core("rpg_stats");

    if (get_class($common->player) != "code_player_rpg") { // because we no longer necessarily have the rpg data
        /*require_once("code/player/code_player_rpg.php");
        $player = new code_player_rpg();*/ // outdated >.>
        $player = $common->core("player");

        if (!$player->get_player($common->player->id)) {
            return '';
        }
    } else {
        $player =& $common->player;
    }

    $skin = $common->page_generation->make_skin("skin_stats", '', 'public');

    if ($_GET['action'] == "spend" && $player->stat_points > 0) {
        $stat = $_POST['stat'];

        $success = $common->rpg_stats->spend($player, $stat);

        if ($success) {
            $message = $skin->stat_increased($stat, $player->$stat);
        } else {
            $code_stats = $common->skin->error_box($common->lang->no_stat_points);
        }
    }

    if ($player->is_member == 1 && $player->stat_points > 0) {
        $stats_table = $skin->stats_table_can_spend($player->stat_points, $player->strength,
            $player->vitality, $player->agility);
    } else {
        $stats_table = $skin->stats_table($player->strength, $player->vitality, $player->agility);
    }

    $table = $skin->common_table($stats_table, $player->level, $player->exp,
        $player->exp_max, $player->hp, $player->hp_percent, $player->energy,
        $player->energy_max, $common->page_generation->format_time($player->registered),
        $common->page_generation->format_time($player->last_active), $message);
    
    return $table;
    
}

?>