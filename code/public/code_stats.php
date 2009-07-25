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
    public function construct() {
        $this->initiate("skin_stats");

        $code_stats = $this->stats_switch();

        parent::construct($code_stats);
    }

    /**
     * spend spend spend
     *
     * @return string html
     */
    protected function stats_switch() {
        if ($this->player->stat_points > 0) {
            $code_stats = $this->spend($_POST['stat']);
        } else {
            $code_stats = $this->skin->error_box($this->skin->lang_error->no_stat_points);
        }
        return $code_stats;
    }

   /**
    * updates stat spend
    *
    * @return string html
    */
    protected function spend($stat) {
        $stats = array("strength", "vitality", "agility");
        if (in_array($stat, $stats)) {
            $this->player->stat_points--;
            $this->player->$stat++;
            $this->player->update_player();
            $spend = $this->skin->stat_increased($stat, $this->player->$stat);
        } else {
            $spend = $this->skin->error_box($this->skin->lang_error->not_a_stat);
        }
        require_once("code/public/code_index.php");
        $code_index = new code_index($this->section, $this->page);
        $code_index->player =& $this->player;
        $code_index->make_skin("skin_index");
        $index_player = $code_index->index_player($spend);

        return $index_player;
    }

    public function stats_table($message = "") {
        if ($this->player->is_member == 1 && $this->player->stat_points > 0) {
            $stats_link = $this->skin->stats_link($this->player->stat_points);
            $stats_table = $this->skin->stats_table_can_spend($this->player->strength, $this->player->vitality, $this->player->agility);
        } else {
            $stats_table = $this->skin->stats_table($this->player->strength, $this->player->vitality, $this->player->agility);
        }
        return $stats_link.$stats_table;
    }

}
?>
