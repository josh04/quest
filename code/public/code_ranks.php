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

        return $code_ranks;
    }

   /**
    * constructs some nice stats for us.
    *
    * @return string html
    */
    public function make_ranks() {

        $most_gold_query = $this->db->execute("SELECT id, username, gold FROM `players` ORDER BY `gold` DESC LIMIT 10");
        while($most_gold = $most_gold_query->fetchrow()) {
            $most_gold_html .= $this->skin->rank_row($most_gold, $most_gold['gold'], "", "gold");
        }

        $make_table .= $this->skin->make_table($most_gold_html, "Richest");

        $most_kills_query = $this->db->execute("SELECT id, username, kills FROM `players` ORDER BY `kills` DESC LIMIT 10");
        while($most_kills = $most_kills_query->fetchrow()) {
            $most_kills_html .= $this->skin->rank_row($most_kills, $most_kills['kills'], "", "kills");
        }

        $make_table .= $this->skin->make_table($most_kills_html, "Assassins");

        $most_deaths_query = $this->db->execute("SELECT id, username, deaths FROM `players` ORDER BY `deaths` DESC LIMIT 10");
        while($most_deaths = $most_deaths_query->fetchrow()) {
            $most_deaths_html .= $this->skin->rank_row($most_deaths, $most_deaths['deaths'], "", "deaths");
        }

        $make_table .= $this->skin->make_table($most_deaths_html, "Quitters");

        $rankers = $this->hooks->get("ranks/custom", HK_ARRAY);

        if($rankers) {
            foreach($rankers as $ranker) {
                $result = $this->db->execute($ranker['sql']);

                while($row = $result->fetchrow()) {
                    $show = $row[($ranker['rank'])];
                    if(function_exists($ranker['function'])) {
                        $show = call_user_func($ranker['function'], $show);
                    }
                    $table .= $this->skin->rank_row($row, $show, $ranker['head'], $ranker['tail']);
                }

                $make_table .= $this->skin->make_table($table, $ranker['title']);
                $table = "";
            }
        }

        $make_ranks = $this->skin->make_ranks($make_table);

        return $make_ranks;
    }

   /**
    * m-m-m-menu time!
    *
    * @param code_menu $menu menu object
    * @param string $label the name of our slot
    */
    public static function code_ranks_menu(&$menu, $label) {
        require_once("skin/default/public/skin_ranks.php");
        $online_query = $menu->db->execute("SELECT count(*) AS c FROM players WHERE (last_active > (".(time()-(60*15))."))");
        $online_count = $online_query->fetchrow();
        $money_query = $menu->db->execute("SELECT sum(`gold`) AS g FROM players");
        $money_sum = $money_query->fetchrow();

        $menu->bottom .= skin_ranks::make_guest_stats($online_count['c'], $money_sum['g']);

        return $label;
    }
}
?>
