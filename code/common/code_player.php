<?php
/**
 * player.class.php
 *
 * makes the player object
 * @package code_common
 * @author josh04
 */

class code_player {

  public $db;
  public $is_member = false;
  public $friends = array();

  /**
   * Main player function. Used to generate the player who is playing.
   *
   * (TODO) I think some of the code_login procedure should end up in here instead. 
   *
   * @return bool good to go?
   */
    public function make_player() {
        if ($_COOKIE['user_id']) {
            $id = $_COOKIE['user_id'];
        } else {
            $id = $_SESSION['user_id'];
        }

        $player_query = $this->db->execute("SELECT * FROM players WHERE id=?", array(intval($id)));

        $player_db = $player_query->fetchrow();

        $check = md5($player_db['id'].$player_db['password'].$player_db['login_rand']);

        if ($check == $_COOKIE['cookie_hash'] || $check == $_SESSION['hash']) {
            $this->is_member = true;
            $last_active = time();

            $mail_count_query = $this->db->execute("SELECT count(*) AS c FROM mail WHERE `to`=? AND `status`=0", array($player_db['id']));
            $mail_count = $mail_count_query->fetchrow();
            if ($mail_count) {
                $player_db['unread'] = $mail_count['c'];
            } else {
                $player_db['unread'] = 0;
            }
            
            $player_db['last_active'] = $last_active;

            $this->player_db_to_object($player_db);
            $update_player['last_active'] = $last_active;
            $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->id);
          
            if ($this->halt_if_suspended()) {
                return false;
            }
        }
        return $this->halt_if_guest();
    }


  /**
   * cancels all if player is suspended. don't like it.
   * (TODO) error handling.
   *
   * @return bool suspended?
   */
    private function halt_if_suspended() {
        if ($this->disabled == "1" && $page_name !="ticket") {
            return true;
        }
        return false;
    }

   /**
    * is guest? is this a guest page?
    *
    * @return bool kick them out?
    */
    private function halt_if_guest() {
        $guest_pages = array("login","guesthelp","globalstats", "index", "");
        if(!$this->is_member && !in_array($_GET['page'],$guest_pages)){
            return false;
        }
        return true;
    }

  /**
   * secret function to make a player object
   *
   * @param array $player_db array from database
   */
    private function player_db_to_object($player_db) {
        foreach($player_db as $key=>$value) { //Fill out our object.
            $this->$key = $value;
        }

        $this->exp_percent = intval(($this->exp / $this->exp_max) * 100);
        $this->registered_date = date("l, jS F Y", $this->registered);
        $this->registered_days = intval((time() - $this->registered)/84600);
    }

  /**
   * get user by id
   *
   * @param integer $id player id
   * @return boolean suceed/fail
   */
    public function get_player_by_id($id) {
        $player_query = $this->db->execute("SELECT * FROM players WHERE id=?", array(intval($id)));
        if ($player_query->recordcount() == 0) {
            return false;
        }
        $player_db = $player_query->fetchrow();
        $this->player_db_to_object($player_db);
        return true;
    }

  /**
   * get user by id
   *
   * @param integer $id player id
   * @return boolean succeed/fail
   */
    public function get_player_by_name($name) {
        $player_query = $this->db->execute("SELECT * FROM players WHERE username=?", array($name));
        if ($player_query->recordcount() == 0) {
            return false;
        }
        $player_db = $player_query->fetchrow();
        $this->player_db_to_object($player_db);
        return true;
    }

   /**
    * commits changes to the database - incorporates levelling up
    *
    * @return bool levelled up?
    */
    public function update_player() {
        $levelled_up = false;

        $update_player['energy'] = $this->energy;
        $update_player['exp'] = $this->exp;
        $update_player['gold'] = $this->gold;
        $update_player['deaths'] = $this->deaths;
        $update_player['kills'] = $this->kills;
        $update_player['hp'] = $this->hp;


        if ($this->exp > $this->exp_max) {
            $this->level++;
            $this->exp_max = $this->exp_max + ($this->level * 70) - 20;
            $this->stat_points = $this->stat_points + 3;
            $this->hp_max = $this->hp_max + rand(5,15) + intval($this->vitality / 2);
            $levelled_up = true;
        }
        
        $update_player['level'] = $this->level;
        $update_player['exp_max'] = $this->exp_max;
        $update_player['stat_points'] = $this->stat_points;
        $update_player['hp_max'] = $this->hp_max;

        //Update victor (the loser)
        $player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id='.$this->id);
        return $levelled_up;
    }

   /**
    * notify the player
    *
    */
    public function add_log($message) {
        $insert_log['player_id'] = $this->id;
        $insert_log['message'] = $message;
        $insert_log['time'] = time();
        $log_query = $this->db->AutoExecute('user_log', $insert_log, 'INSERT');
    }

function getfriends(){

    $query = $this->db->execute("SELECT f.*, p.username
                           FROM friends AS f
                           LEFT JOIN players AS p
                           ON f.friend_id=p.id
                           WHERE f.player_id=? 
                           AND f.validated=1", 
                           array($this->id));
                           
    if ($query->recordcount() == 0) {
	   return false;
    } else {
	   while($friend = $query->fetchrow()) {
	  	  $this->friends[] = $friend;
	   }
	   return true;
	  }
}

}
?>
