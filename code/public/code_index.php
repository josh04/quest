<?php
/**
 * code_index.class.php
 *
 * generate the page index
 * @package code_public
 * @author josh04
 */
class code_index extends code_common {
    
    public $player_class = "code_player_rpg";
    

   /**
    * builds the logged-in index
    *
    * @param string $message error message
    * @return string html
    */
    public function index_player($message = "") {
        /*
        require_once("code/public/code_stats.php"); //(TODO) not site-independent
        $code_stats = new code_stats($this->section, $this->page);
        $code_stats->player =& $this->player;
        $code_stats->core("page_generation");
        $code_stats->skin =& $code_stats->page_generation->make_skin('skin_stats');
        $stats = $code_stats->stats_table($message);
*/
        $this->core("hooks");

        $extra = $this->hooks->get("home/extra");

        $online_list = $this->online_list();

        $header  = $this->hooks->get("home/header");
        $header .= $this->quest();

        $log = $this->log();
        $mail = $this->mail();

        $index_player = $this->skin->index_player($this->player, $header, $stats, $online_list, $log, $mail, $extra);
        return $index_player;
    }

   /**
    * builds the online list
    *
    * @return string html
    */
    public function online_list() {
        $online_query = $this->db->execute("SELECT id, username FROM players WHERE (last_active > (?))", array((time()-(60*15))));

        $online_list = "";
        while($online = $online_query->fetchrow()) {
            $online_list[] = $this->skin->member_online_link($online['id'], $online['username']);
        }
        
        return implode(", ",$online_list);
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
    * current quest
    *
    * @return string html
    */
    public function quest() {
        preg_match("/\A([0-9]+):([0-9a-z]+)/is",$this->player->quest,$m);
        if(!$m[1]) return '';
        $currentq = $this->db->execute("SELECT * FROM `quests` WHERE `id`=?",array($m[1]));
        if($currentq->numrows()!=1) return '';
        $quest = $this->skin->current_quest($currentq->fetchrow());
        return $quest;
    }

   /**
    * recent stuff from your log
    *
    * @return string html
    */
    public function log() {
        $log_query = $this->db->execute("SELECT `message`, `status` FROM `user_log` WHERE `player_id`=? ORDER BY `time` DESC LIMIT 5",array($this->player->id));

        if (!$log_query) {
            $log = $this->skin->log_entry($this->lang->error_getting_log, 0);
        }
        
        if ($log_query->numrows() == 0) {
            $log = $this->skin->log_entry($this->lang->no_laptop_message, 0);
        }

        while($log_entry = $log_query->fetchrow()) {
            $log .= $this->skin->log_entry($log_entry['message'], $log_entry['status']);
        }

        return $log;
    }

   /**
    * recent mail messages
    *
    * @return string html
    */
    public function mail() {
        $mail_query = $this->db->execute("SELECT `mail`.`id`, `mail`.`from`, `mail`.`status`, `mail`.`subject`, `players`.`username`
                          FROM `mail`
                          INNER JOIN `players` ON `players`.`id` = `mail`.`from`
                          WHERE `to`=? ORDER BY `time` DESC LIMIT 5",array($this->player->id));

        if (!$mail_query) {
            $this->skin->log_entry($this->lang->error_getting_mail, 0);
        }

        if ($mail_query->numrows()==0) {
            $mail = $this->skin->log_entry($this->lang->no_messages_long, 0);
        }

        while($mail_row = $mail_query->fetchrow()) {
            $mail_row['subject'] = str_replace(array("<",">"),array("&lt;","&gt;"),$mail_row['subject']);
            $mail .= $this->skin->mail_entry($mail_row);
        }

        return $mail;
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
            if (get_class($menu->player) != "code_player_rpg") { // because we no longer necessarily have the rpg data
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
