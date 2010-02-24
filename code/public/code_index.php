<?php
/**
 * code_index.class.php
 *
 * generate the page index
 * @package code_public
 * @author josh04
 */
class code_index extends code_common {

   /**
    * builds the logged-in index
    *
    * @param string $message error message
    * @return string html
    */
    public function index_player($message = "") {

        $header = $this->hooks->get("home/header");
        $extra = $this->hooks->get("home/extra");
        

        $index_player = $this->skin->index_player($this->player->username, $header, $extra);
        return $index_player;
    }

   /**
    * builds the site news
    *
    * @return string html
    */
    public function news() {
        $news_query = $this->db->execute("SELECT n.*, p.username FROM news AS n
                                    LEFT JOIN players AS p ON n.player_id=p.id
                                    ORDER BY n.date DESC");
        $news = ($news_query) ? "" : "Error retrieving news.";

        while($news_entry = $news_query->fetchrow()) {
            $news .= $this->skin->news_entry($news_entry['id'], $news_entry['username'], $news_entry['message']);
        }

        return $news;
    }

   /**
    * builds the guest index. losers :P
    *
    * @return string html
    */
    public function index_guest($login_error) {
        if ($_GET['action'] == "logged_out") {
            $login_error = $this->lang->logged_out;
        }
        $index_guest = $this->skin->index_guest($username, $login_error, $this->settings->get['welcometext']);
        return $index_guest;
    }

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index");
        
        if ($this->player->is_member) {
            $code_index = $this->index_player();
        } else {
            $code_index = $this->index_guest("", "");
        }
        
        return $code_index;
    }

   /**
    * Static menu extension for the index.
    *
    * @param code_menu $menu Menu object
    * @param string $label Text for the label
    * @return string html
    */
    public static function code_index_menu(&$menu, $label) { //(TODO) not site-independent
        if ($menu->player->is_member) {
            if (!isset($menu->player->level)) { // because we no longer necessarily have the rpg data
                $player_query = $menu->db->execute("SELECT * FROM `rpg` WHERE `player_id`=?", array($menu->player->id));
                
                if (!$player_extra = $player_query->fetchrow()) {
                    return $label;
                }
                $player_extra['exp_diff'] = $player_extra['exp_max'] - $player_extra['exp'];
            } else {
                $player_extra['hp'] = $menu->player->hp;
                $player_extra['strength'] = $menu->player->strength;
                $player_extra['vitality'] = $menu->player->vitality;
                $player_extra['agility'] = $menu->player->agility;

                $player_extra['hp'] = $menu->player->hp;
                $player_extra['hp_max'] = $menu->player->hp_max;
                $player_extra['exp'] = $menu->player->exp;
                $player_extra['exp_max'] = $menu->player->exp_max;
                $player_extra['exp_diff'] = $menu->player->exp_max - $menu->player->exp;
                $player_extra['energy'] = $menu->player->energy;
                $player_extra['energy_max'] = $menu->player->energy_max;

                $player_extra['kills'] = $menu->player->kills;
                $player_extra['deaths'] = $menu->player->deaths;

                $player_extra['level'] = $menu->player->level;
                $player_extra['stat_points'] = $menu->player->stat_points;
            }

            require_once("skin/public/skin_index.php");
            $menu->top .= skin_index::player_details_menu($menu->player, $player_extra);
        }
        return $label;
    }
}

?>
