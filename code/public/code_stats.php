<?php
/**
 * (TODO): This code friggin' sucks.
 * (DONE): This should be POST not GET, certainly.
 * code_stats.php
 *
 * lets the user l-l-level up.
 * @author josh04
 * @package code_public
 */
class code_stats extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct_page() {
        $this->initiate("skin_stats");

        if ($_GET['action'] == 'spend' && $this->player->stat_points > 0) {
            $code_stats = $this->spend();
        } else {
            $code_stats = $this->stats_page("");
        }

        parent::construct_page($code_stats);
    }

   /**
    * displays stats page
    *
    * @param string $stats_message stats updated message
    * @return string html
    */
    public function stats_page($stats_message) {
        if ($this->player->stat_points) {
            $stats_page = $this->skin->stats_to_spend($this->player->stat_points, $stats_message);
        } else {
            $stats_page = $this->skin->stats_none($stats_message);
        }
        return $stats_page;
    }

   /**
    * updates stat spend
    *
    * @return string html
    */
    public function spend() {
        switch((intval($_POST['stat']))) {
            case 'Strength':
                $increase_stat = $this->increase_stat('strength');
                break;
            case 'Vitality':
                $increase_stat = $this->increase_stat('vitality');
                break;
            case 'Agility':
                $increase_stat = $this->increase_stat('agility');
                break;
        }

        $spend = $this->stats_page($increase_stat);

        return $spend;
    }

    /**
    * increases stat by a point
    *
    * @param string $stat which stat?
    * @return string html
    */
    public function increase_stat($stat) {
        $this->player->stat_points--;
        $this->player->$stat++;
        $update_player['stat_points'] = $this->player->stat_points;
        $update_player[$stat] = $this->player->$stat;
        $stat_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id='.$this->player->id);

        if ($stat_query) {
            $increase_stat = "You have increased your ".$stat.". It is now at ".$this->player->$stat.".";
        } else {
            $increase_stat = "Error updating player's ".$stat.".";
        }

        return $increase_stat;
    }
}
?>
