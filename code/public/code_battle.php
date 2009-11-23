<?php
/**
 * oooooh shit this is the bad one. fight!
 *
 * @author josh04
 * @package code_public
 */
class code_battle extends code_common {

    public $enemy;
    public $player_class = "code_player_rpg";

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_battle");

        $this->fight = new code_fight($this->section, $this->page);
        $code_battle = $this->battle_switch();

        return $code_battle;
    }
    
   /**
    * which way shall we turn?
    * 
    * @return string html
    */
    public function battle_switch() {
        switch($_GET['action']) {
            case 'search':
                $battle_search = $this->battle_search();
                return $battle_search;
            case 'fight':
                $battle_search = $this->battle();
                return $battle_search;
        }
        
        $battle_search = $this->battle_search_page();
        return $battle_search;
    }
    
   /**
    * find us a player
    *
    * @param string $message error message
    * @return string html
    */
    public function battle_search() {
		
	//Construct query
        $player_query_construct = "SELECT `p`.`id`, `p`.`username`, `r`.`hp`, `r`.`hp_max`, `r`.`level`
            FROM `rpg` AS `r`
            LEFT JOIN `players` AS `p`
            ON `r`.`player_id`=`p`.`id`
            WHERE `r`.`player_id` != ? ";
        $player_query_values = array($this->player->id);

        if ($_POST['username']) {
            $player_query_construct .= " AND `p`.`username` LIKE ? ";
            $player_query_values[] = "%".trim($_POST['username'])."%";
        }
        
        //Add level range for search
        if ($_POST['level_min']) {
            $player_query_construct .= " AND `r`.`level` >= ? ";
            $player_query_values[] = intval($_POST['level_min']);
        }
        
        if ($_POST['level_max']) {
            $player_query_construct .= " AND `r`.`level` <= ? ";
            $player_query_values[] = intval($_POST['level_max']);
        }
        
        switch($_POST['alive']) {
            case 0:
                $player_query_construct .= " AND `r`.`hp` = 0 ";
                break;
            case 1:
                $player_query_construct .= " AND `r`.`hp` > 0 ";
                break;
        }

        $player_query_construct .= "LIMIT 0,20";
        
	$player_query = $this->db->execute($player_query_construct, $player_query_values); //Search!

        if ($player_query->recordcount()) {
            while ($player = $player_query->fetchrow()) {
                $player_rows .= $this->skin->battle_search_row($player);
            }
            $player_html = $this->skin->battle_search_row_wrap($player_rows);
        } else {
            $player_html = $this->skin->error_box($this->lang->no_players_found);
        }

        $battle_search_page = $this->battle_search_page();

        $battle_search = $battle_search_page.$player_html;

        return $battle_search;
    }
    
   /**
    * battle search form
    * 
    * @return string html
    */
    public function battle_search_page($message = "") {
        $username = htmlentities($_POST['username'],ENT_QUOTES,'UTF-8');
        $level_min = intval($_POST['level_min']);
        $level_max = intval($_POST['level_max']);
        $battle_search_page = $this->skin->battle_search_page($username, $level_min, $level_max, $message);
        return $battle_search_page;
    }

    public function battle() {

        $id = intval($_POST['id']);

        if(!$id && isset($_POST['username'])) {
            $test_player = new code_player;
            $test_player->get_player($_POST['username']);
            if(isset($test_player->id)) {
                $id = $test_player->id;
            } else {
                $fight = $this->battle_search_page($this->skin->error_box($this->lang->no_player_selected));
                return $fight;
            }
        }

        if (!$id) {
            $fight = $this->battle_search_page($this->skin->error_box($this->lang->no_player_selected));
            return $fight;
        }

        if ($id == $this->player->id) {
            $fight = $this->battle_search_page($this->skin->error_box($this->lang->cannot_attack_self));
            return $fight;
        }

        //Player cannot attack any more
        if ($this->player->energy == 0) {
            $fight = $this->battle_search_page($this->skin->error_box($this->lang->player_no_energy));
            return $fight;
        }

        //Player is unconscious
        if ($this->player->hp == 0) {
            $fight = $this->battle_search_page($this->skin->error_box($this->lang->player_currently_incapacitated));
            return $fight;
        }



        require_once("code/common/code_fight.php");
        $this->fight = new code_fight($this->section, $this->page);
        
        $this->fight->enemy = new code_player_rpg;
        $this->fight->type = "player";
        if (!$this->fight->enemy->get_player($id)) {
            $fight = $this->battle_search_page($this->skin->error_box($this->lang->player_not_found));
            return $fight;
        }
        
        //Otherwise, check if agent has any health
        if ($this->fight->enemy->hp == 0) {
            $fight = $this->battle_search_page($this->skin->error_box($this->lang->enemy_currently_incapacitated));
            return $fight;
        }
        
        $battle = $this->fight->battle();
        
        return $battle;
    }

}
?>
