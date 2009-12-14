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
        $player_query = $common->db->execute("SELECT * FROM `rpg` WHERE `player_id`=?", array($menu->player->id));

        if (!$player_extra = $player_query->fetchrow()) {
            return '';
        }

        $level = $player_extra['level'];
        $exp = $player_extra['exp'];
        $exp_max = $player_extra['exp_max'];
        $hp = $player_extra['hp'];
        $hp_percent = intval($player_extra['hp']/$player_extra['hp_max'] * 100);
        $energy = $player_extra['energy'];
        $energy_max = $player_extra['energy_max'];
        $registered = $player_extra['registered'];
        $last_active = $player_extra['last_active'];
    } else {
        $level = $common->player->level;
        $exp = $common->player->exp;
        $exp_max = $common->player->exp_max;
        $hp = $common->player->hp;
        $hp_percent = intval($common->player->hp/$common->player->hp_max * 100);
        $energy = $common->player->energy;
        $energy_max = $common->player->energy_max;
        $registered = $common->player->registered;
        $last_active = $common->player->last_active;
    }

    $skin = $common->page_generation->make_skin("skin_stats", '', 'public');

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

    $table = $skin->common_table($stats_table, $level, $exp,
        $exp_max, $hp, $hp_percent, $energy,
        $energy_max, $common->page_generation->format_time($registered),
        $common->page_generation->format_time($last_active), $message);

    return $table;
    
}

?>