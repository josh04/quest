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
    
    $skin = $common->page_generation->make_skin("skin_stats");

    if ($_GET['action'] == "spend" && $common->player->stat_points > 0) {
        $success = $common->rpg_stats->spend($_POST['stat']);

        if ($success) {
            $message = $skin->stat_increased($stat, $this->player->$stat);
        } else {
            $code_stats = $this->skin->error_box($this->lang->no_stat_points);
        }
    }

    if ($common->player->is_member == 1 && $common->player->stat_points > 0) {
        $stats_table = $skin->stats_table_can_spend($common->player->stat_points, $common->player->strength,
            $common->player->vitality, $common->player->agility);
    } else {
        $stats_table = $skin->stats_table($common->player->strength, $common->player->vitality, $common->player->agility);
    }

    $table = $skin->common_table($stats_table, $common->player->level, $common->player->exp,
        $common->player->exp_max, $common->player->hp, $common->player->hp_percent, $common->player->energy,
        $common->player->energy_max, $common->page_generation->format_time($common->player->registered),
        $common->page_generation->format_time($common->player->last_active), $message);

    return $table;
    
}

?>