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
        require_once("code/public/code_stats.php");
        $code_stats = new code_stats($this->section, $this->page);
        $code_stats->player =& $this->player;
        $code_stats->make_skin('skin_stats');
        $stats = $code_stats->stats_table($message);

        $news = "";
        $news = $this->news();

        $online_list = $this->online_list();
        $quest = $this->quest();

        $index_player = $this->skin->index_player($this->player, $stats, $news, $online_list, $quest);
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
            $online_list .= $this->skin->member_online_link($online['id'], $online['username']);
        }
        
        return $online_list;
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
    * builds the guest index. losers :P
    *
    * @return string html
    */
    public function index_guest($login_error) {
        if ($_GET['action'] == "logged_out") {
            $login_error = $this->skin->lang_error->logged_out;
        }
        $index_guest = $this->skin->index_guest($username, $login_error, $this->settings['welcometext']);
        return $index_guest;
    }

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index");
        if($this->player->is_member) {
            $code_index = $this->index_player();
        } else {
            $code_index = $this->index_guest("", "");
        }
        
        parent::construct($code_index);
    }
}

?>
