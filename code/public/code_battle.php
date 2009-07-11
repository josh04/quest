<?php
/**
 * oooooh shit this is the bad one. fight!
 *
 * @author josh04
 * @package code_public
 */
class code_battle extends code_common {

    public $enemy;

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_battle");

        $code_battle = $this->battle_switch();

        parent::construct($code_battle);
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
                if ($_POST['username']) {
                    $battle_search = $this->fight_by_name();
                    return $battle_search;
                }
                $battle_search = $this->fight();
                return $battle_search;
        }
        
        $battle_search = $this->battle_search_page();
        return $battle_search;
    }
    
   /**
    * find us a player
    * 
    * @return string html
    */
    public function battle_search() {
		
		//Construct query
		$player_query_construct = "SELECT id, username, hp, hp_max, level
               FROM players
               WHERE id != ? ";
        $player_query_values = array($this->player->id);

        if ($_POST['username']) {
            $player_query_construct .= " AND username LIKE ? ";
            $player_query_values[] = "%".trim($_POST['username'])."%";
        }
        
		//Add level range for search
		if ($_POST['level_min']) {
            $player_query_construct .= " AND level >= ? ";
			$player_query_values[] = intval($_POST['level_min']);
		}
        
        if ($_POST['level_max']) {
            $player_query_construct .= " AND level <= ? ";
			$player_query_values[] = intval($_POST['level_max']);
		}
        
        switch($_POST['alive']) {
            case 0:
                $player_query_construct .= " AND hp = 0 ";
                break;
            case 1:
                $player_query_construct .= " AND hp > 0 ";
                break;
        }

        $player_query_construct .= "LIMIT 0,20";

		$player_query = $this->db->execute($player_query_construct, $player_query_values); //Search!
        print $this->db->ErrorMsg();
        if ($player_query->recordcount()) {
            while ($player = $player_query->fetchrow()) {
                $player_rows .= $this->skin->battle_search_row($player);
            }
            $player_html = $this->skin->battle_search_row_wrap($player_rows);
        } else {
            $player_html = $this->skin->warning_page($this->skin->lang_error->player_not_found);
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
    public function battle_search_page() {
        $username = htmlentities($_POST['username'],ENT_COMPAT,'UTF-8');
        $level_min = intval($_POST['level_min']);
        $level_max = intval($_POST['level_max']);
        $battle_search_page = $this->skin->battle_search_page($username, $level_min, $level_max);
        return $battle_search_page;
    }

    public function fight_by_name() {
        $this->enemy = new code_player;
        $this->enemy->db =& $this->db;

        if (!$this->enemy->get_player_by_name($_POST['username'])) {
            $fight_by_name = "User not found.";
            return $fight_by_name;
        }
        $fight_by_name = $this->fight($this->enemy->id);
        return $fight_by_name;
    }
   
   /**
    * FIIIIGHT. finally.
    * 
    * @return string html
    */
    public function fight() {
        $id = $this->enemy->id;
        if (!$id) {
            $id = intval($_POST['id']);
        }

        if (!$id) {
			$fight = $this->skin->warning_page($this->skin->lang_error->no_player_selected);
            return $fight;
        }

        if ($id == $this->player->id) {
			$fight = $this->skin->warning_page($this->skin->lang_error->cannot_attack_self);
            return $fight;
        }

        //Player cannot attack any more
        if ($this->player->energy == 0) {
			$fight = $this->skin->warning_page($this->skin->lang_error->player_no_energy);
            return $fight;
        }
        
        //Player is unconscious
        if ($this->player->hp == 0) {
			$fight = $this->skin->warning_page($this->skin->lang_error->player_currently_incapacitated);
            return $fight;
        }
        if (!$this->enemy) {
            $this->enemy = new code_player;
            $this->enemy->db =& $this->db;

            if (!$this->enemy->get_player_by_id($id)) {
                $fight = $this->skin->warning_page($this->skin->lang_error->player_not_found);
                return $fight;
            }
        }

        //Otherwise, check if agent has any health
        if ($this->enemy->hp == 0) {
			$fight = $this->skin->warning_page($this->skin->lang_error->enemy_currently_incapacitated);
            return $fight;
        }

		//Get enemies bonuses from equipment
		$enemy_attack_query = $this->db->query("SELECT blueprints.effectiveness, blueprints.name
            FROM `items`, `blueprints`
            WHERE blueprints.id=items.item_id AND items.player_id=? AND blueprints.type='weapon' AND items.status='equipped'",
            array($this->enemy->id));
        $enemy_weapon = $enemy_attack_query->fetchrow();
		$this->enemy->attack_bonus = $enemy_weapon['effectiveness'];
		$enemy_defense_query = $this->db->query("select blueprints.effectiveness, blueprints.name
            FROM `items`, `blueprints` 
            WHERE blueprints.id=items.item_id AND items.player_id=? AND blueprints.type='armour' AND items.status='equipped'",
            array($this->enemy->id));
        $enemy_armour = $enemy_defense_query->fetchrow();
		$this->enemy->defense_bonus = $enemy_armour['effectiveness'];
		
		//Get player's bonuses from equipment
		$player_attack_query = $this->db->query("SELECT blueprints.effectiveness, blueprints.name
            FROM `items`, `blueprints` 
            WHERE blueprints.id=items.item_id AND items.player_id=? AND blueprints.type='weapon' AND items.status='equipped'",
            array($this->player->id));
        $player_weapon = $player_attack_query->fetchrow();
		$this->player->attack_bonus = $player_weapon['effectiveness'];
		$player_defense_query = $this->db->query("select blueprints.effectiveness, blueprints.name
            FROM `items`, `blueprints` 
            WHERE blueprints.id=items.item_id AND items.player_id=? AND blueprints.type='armour' AND items.status='equipped'",
            array($this->player->id));
        $player_armour = $player_defense_query->fetchrow();
		$this->player->defense_bonus = $player_armour['effectiveness'];
        

        //Calculate some variables that will be used
		if (($this->enemy->strength - $this->player->strength) > 0) {
            $this->enemy->strength_bonus = $this->enemy->strength - $this->player->strength;
        } else {
            $this->player->strength_bonus = $this->player->strength - $this->enemy->strength;
        }
		
        if (($this->enemy->vitality - $this->player->vitality) > 0) {
            $this->enemy->vitality_bonus = $this->enemy->vitality - $this->player->vitality;
        } else {
            $this->player->vitality_bonus = $this->player->vitality - $this->enemy->vitality;
        }
        
		if (($this->enemy->agility - $this->player->agility) > 0) {
            $this->enemy->agility_bonus = $this->enemy->agility - $this->player->agility;
        } else {
            $this->player->agility_bonus = $this->player->agility - $this->enemy->agility;
        }
        
		$strength_total = $this->enemy->strength + $this->player->strength;
		$vitality_total = $this->enemy->vitality + $this->player->vitality;
		$agility_total  = $this->enemy->agility + $this->player->agility;
        
		//Calculate the damage to be dealt by each this->player (dependent on strength and vitality)
		$this->enemy->damage_max = 2*$this->enemy->strength + $this->enemy->attack_bonus - $this->player->defense_bonus;
		$this->enemy->damage_max = $this->enemy->damage_max - intval($this->enemy->damage_max * ($this->player->vitality / $vitality_total) );
        
        $this->enemy->damage_min = $this->enemy->damage_max - ($this->player->agility_bonus/2); //Damage range +- agil bonus
        
		$this->enemy->damage_max = $this->enemy->damage_max + ($this->enemy->agility_bonus/2) + ($this->enemy->strength_bonus * 2);
        
        $this->player->damage_max = ($this->player->strength * 2) + $this->player->attack_bonus - $this->enemy->defense_bonus;
		$this->player->damage_max = $this->player->damage_max - intval($this->player->damage_max * ($this->enemy->vitality / $vitality_total));
        
        $this->player->damage_min = $this->player->damage_max - ($this->enemy->agility_bonus/2); //Damage range +- agil bonus
        
		$this->player->damage_max = $this->player->damage_max + ($this->player->agility_bonus/2) + ($this->player->strength_bonus * 2);
        
        // are we negative?
        if ($this->player->damage_min < 0) {
            $this->player->damage_min = 0;
        }
        
        if ($this->player->damage_max <= 0) {
            $this->player->damage_max = 1;
        }
        
        if ($this->enemy->damage_min < 0) {
            $this->enemy->damage_min = 0;
        }
        
        if ($this->enemy->damage_max <= 0) {
            $this->enemy->damage_max = 1;
        }
        
		//Calculate battle 'combos' - how many times in a row a player can attack (dependent on agility)
		$this->enemy->combo_max = $this->enemy->agility_bonus + $this->enemy->vitality_bonus + 3;
        $this->player->combo_max = $this->player->agility_bonus + $this->player->vitality_bonus + 3;
        
		//Calculate the chance to miss opposing player
        
        $this->enemy->miss = $this->player->agility / ($agility_total*5);
        $this->player->miss = $this->enemy->agility / ($agility_total*5);
		
		$battle_rounds = 50; //Maximum number of rounds/turns in the battle. (TODO) Changed in admin panel?

		//While somebody is still awake, fight!
		while ($this->enemy->hp > 0 && $this->player->hp > 0 && $battle_rounds > 0) {
            
            if ( ($this->player->agility + $this->player->hp) >= ($this->enemy->agility + $this->player->hp) ) { 
                $attacking =& $this->player;
                $defending =& $this->enemy;
            } else {
                $attacking =& $this->enemy;
                $defending =& $this->player;
            }

			for ($i=0; $i<$attacking->combo_max; $i++) {
				//Chance to miss?
				$miss_chance = mt_rand(0, 100) / 100;
				if ($miss_chance <= $attacking->miss) {
					$attacks[] = $attacking->username." tried to attack ".$defending->username." but missed.";
				} else {
					$damage = rand($attacking->damage_min, $attacking->damage_max); //Calculate random damage
					$defending->hp -= $damage;
	
					$attacks[] = $attacking->username." attacks ".$defending->username." for <b>".$damage."</b> damage.";
                    if ( $defending->hp > 0 ) {
                        $attacks[] = $defending->username." has ".$defending->hp." hit points left.";
                    } else {
                        $victor =& $attacking;
                        $loser =& $defending;
                        $attacks[] = $defending->username."  is unconscious!";
                        break 2;
                    }
				}
                
				$battle_rounds--;
                
				if ($battle_rounds <= 0) {
                    $attacks[] = "Battle drawn.";
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			for ($i = 0; $i<$defending->combo_max; $i++) {
				//Chance to miss?
				$miss_chance = mt_rand(0, 100) / 100;
				if ($miss_chance <= $defending->miss) {
					$attacks[] = $defending->username." tried to attack ".$attacking->username." but missed.";
				} else {
					$damage = rand($defending->damage_min, $defending->damage_max); //Calculate random damage
					$attacking->hp -= $damage;
	
					$attacks[] = $defending->username." attacks ".$attacking->username." for <b>".$damage."</b> damage.";
                    if ( $attacking->hp > 0 ) {
                        $attacks[] = $attacking->username." has ".$attacking->hp." hit points left.";
                    } else {
                        $victor =& $defending;
                        $loser =& $attacking;
                        $attacks[] = $attacking->username." is unconscious!";
                        break 2;
                    }
				}
                
				$battle_rounds--;
                
				if ($battle_rounds <= 0) {
                    $attacks[] = "Battle drawn.";
					break 2; //Break out of for and while loop, battle is over!
				}
			}
		}

        if ($battle_rounds) {
            $exp_gain = intval(3 *($loser->level + ($loser->level / $victor->level)));
            $gold_shift = intval( rand(1, intval(0.2 * $loser->gold)) );

            $attacks[] = $loser->username." was defeated by ".$victor->username.".";
            $attacks[] = $loser->username." lost ".$gold_shift." tokens.";
            $attacks[] = $victor->username." gained ".$gold_shift." tokens and ".$exp_gain." experience.";
            

            $loser->hp = 0;
            $loser->gold = $loser->gold - $gold_shift;
            $loser->deaths++;
            
            
            $victor->exp = $victor->exp + $exp_gain;
            $victor->gold = $victor->gold + $gold_shift;
            $victor->kills++;
            
            $victor->add_log($this->skin->victory_log($loser->id, $loser->username, $gold_shift, $exp_gain));
            $loser->add_log($this->skin->loss_log($victor->id, $victor->username, $gold_shift));
        } else {
            $this->player->add_log($this->skin->draw_log($this->enemy->id, $this->enemy->username));
            $this->enemy->add_log($this->skin->draw_log($this->player->id, $this->player->username));
        }

        $this->player->energy--; // he started it!
        
        if ($this->player->update_player()) {
            $attacks[] = $this->player->username." gained a level!";
            $this->player->add_log("You gained a level.");
        }

        if ($this->enemy->update_player()) {
            $attacks[] = $this->enemy->username." gained a level!";
            $this->enemy->add_log("You gained a level.");
        }

        foreach ($attacks as $attack) {
            $battle_html .= $this->skin->battle_row($attack);
        }

        $fight = $this->skin->fight($battle_html);
        return $fight;

    }

}
?>
