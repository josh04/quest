<?php
/**
 * Description of code_ranks
 *
 * @author josh04
 * @package code_public
 */
class code_ranks extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_ranks");

        $code_ranks = $this->make_ranks();

        parent::construct($code_ranks);
    }

   /**
    * constructs some nice stats for us.
    *
    * @return string html
    */
    public function make_ranks() {

        $most_gold_query = $this->db->execute("SELECT id, username, gold FROM `players` ORDER BY `gold` DESC LIMIT 10");
        while($most_gold = $most_gold_query->fetchrow()) {
            $most_gold_html .= $this->skin->rank_row($most_gold, "gold");
        }

        $make_table .= $this->skin->make_table($most_gold_html, "Richest");

        $most_kills_query = $this->db->execute("SELECT id, username, kills FROM `players` ORDER BY `kills` DESC LIMIT 10");
        while($most_kills = $most_kills_query->fetchrow()) {
            $most_kills_html .= $this->skin->rank_row($most_kills, "kills");
        }

        $make_table .= $this->skin->make_table($most_kills_html, "Assassins");

        $most_deaths_query = $this->db->execute("SELECT id, username, deaths FROM `players` ORDER BY `deaths` DESC LIMIT 10");
        while($most_deaths = $most_deaths_query->fetchrow()) {
            $most_deaths_html .= $this->skin->rank_row($most_deaths, "deaths");
        }

        $make_table .= $this->skin->make_table($most_deaths_html, "Quitters");

        $make_ranks = $this->skin->make_ranks($make_table);

        return $make_ranks;
    }

}
?>
