<?php
/**
* fight.class.php
*
* allows fights and scuffles to take place
* @package code_common
* @author grego
*/
 
class code_fight extends code_common {
 
  public $db;
  public $enemy;
  public $player_section;
  public $player_page;
  public $player;

  public $type = 'static';
  public $return_value = 'log';
  public $save = true;
  public $save_gold = true;
  public $save_xp = true;
  public $record_kills = true;
  public $use_energy = true;

   /**
    * overrides the constructer to make the skin automatically
    *
    * @param string $section section name
    * @param string $page section page
    * @param array $config config array for db
    */
    public function __construct($section, $page, $config = array()) {
        parent::__construct($section, $page, $config);
        $this->section = 'common';
        $this->page = 'fight';
        $this->player_section = $section;
        $this->player_page = $page;
        $this->initiate("skin_fight");
    }

   /**
    * do battle with another player or an NPC
    *
    * @return success or failure
    */
    public function battle() {


        if (!method_exists($this->enemy,"add_log")) {
            $fight = $this->error_page($this->skin->lang_error->enemy_malformed);
            return $fight;
        }

        if ($this->type == "player") {
            $this->player_combat_setup();
        }

        // Get player's bonuses from equipment
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
		
        $battle_rounds = 50; // Maximum number of rounds/turns in the battle. (TODO) Changed in admin panel?

        $this->player->battle_function = "player_battle_row";
        $this->enemy->battle_function = "enemy_battle_row";

        //While somebody is still awake, fight!
        while ($this->enemy->hp > 0 && $this->player->hp > 0 && $battle_rounds > 0) {
            
            if ( ($this->player->agility + $this->player->hp) >= ($this->enemy->agility + $this->player->hp) ) { 
                $attacking =& $this->player;
                $defending =& $this->enemy;
            } else {
                $attacking =& $this->enemy;
                $defending =& $this->player;
            }

            $battle_function = strval($attacking->battle_function);

            for ($i=0; $i<$attacking->combo_max; $i++) {
                //Chance to miss?
                $miss_chance = mt_rand(0, 100) / 100;
                if ($miss_chance <= $attacking->miss) {
                    $attacks[] = $this->skin->$battle_function($attacking->username." tried to attack ".$defending->username." but missed.");
                } else {
                    $damage = rand(intval($attacking->damage_min), intval($attacking->damage_max)); //Calculate random damage
                    $defending->hp -= $damage;

                    $attacks[] = $this->skin->$battle_function($attacking->username." attacks ".$defending->username." for <b>".$damage."</b> damage.");
                    if ( $defending->hp > 0 ) {
                        $attacks[] = $this->skin->$battle_function($defending->username." has ".$defending->hp." hit points left.");
                    } else {
                        $victor =& $attacking;
                        $loser =& $defending;
                        $attacks[] = $this->skin->$battle_function($defending->username."  is unconscious!");
                        break 2;
                    }
                }

                $battle_rounds--;

                if ($battle_rounds <= 0) {
                    $attacks[] = $this->skin->battle_row($this->skin->lang_error->battle_drawn);
                    break 2; //Break out of for and while loop, battle is over!
                }
            }

            $battle_function = $defending->battle_function;

            for ($i = 0; $i<$defending->combo_max; $i++) {
                //Chance to miss?
                $miss_chance = mt_rand(0, 100) / 100;
                if ($miss_chance <= $defending->miss) {
                    $attacks[] = $this->skin->$battle_function($defending->username." tried to attack ".$attacking->username." but missed.");
                } else {
                    $damage = rand($defending->damage_min, $defending->damage_max); //Calculate random damage
                    $attacking->hp -= $damage;

                    $attacks[] = $this->skin->$battle_function($defending->username." attacks ".$attacking->username." for <b>".$damage."</b> damage.");
                    if ( $attacking->hp > 0 ) {
                        $attacks[] = $this->skin->$battle_function($attacking->username." has ".$attacking->hp." hit points left.");
                    } else {
                        $victor =& $defending;
                        $loser =& $attacking;
                        $attacks[] = $this->skin->$battle_function($attacking->username." is unconscious!");
                        break 2;
                    }
                }

                $battle_rounds--;

                if ($battle_rounds <= 0) {
                    $attacks[] = $this->skin->battle_row($this->skin->lang_error->battle_drawn);
                    break 2; //Break out of for and while loop, battle is over!
                }
            }
        }
        $victor->victory = true;
        $loser->victory = false;

        $battle_function = $victor->battle_function;
        
        if ($battle_rounds) {
            $exp_gain = abs(intval(3 *($loser->level + ($loser->level / $victor->level))));
            $gold_shift = abs(intval( rand(1, intval(0.2 * $loser->gold))));

            $attacks[] = $this->skin->$battle_function($loser->username." was defeated by ".$victor->username.".");
            $attacks[] = $this->skin->$battle_function($loser->username." lost ".$gold_shift." tokens.");
            $attacks[] = $this->skin->$battle_function($victor->username." gained ".$gold_shift." tokens and ".$exp_gain." experience.");
            

            $loser->hp = 0;
            if ($this->save_gold) {
                $loser->gold = $loser->gold - $gold_shift;
            }
            
            if ($this->record_kills) {
                $loser->deaths++;
            }
            
            if ($this->save_xp) {
                $victor->exp = $victor->exp + $exp_gain;
            }
            
            if ($this->save_gold) {
                $victor->gold = $victor->gold + $gold_shift;
            }
            
            if ($this->record_kills) {
                $victor->kills++;
            }
            
            $victor->add_log($this->skin->victory_log($loser->id, $loser->username, $gold_shift, $exp_gain));
            $loser->add_log($this->skin->loss_log($victor->id, $victor->username, $gold_shift));
        } else {
            $this->player->add_log($this->skin->draw_log($this->enemy->id, $this->enemy->username));
            $this->enemy->add_log($this->skin->draw_log($this->player->id, $this->player->username));
            $banner = $this->skin->error_box($this->skin->lang_error->you_drew);
        }

        if ($this->use_energy) {
            $this->player->energy--; // he started it!
        }
        
        if ($this->player->update_player()) {
            $attacks[] = $this->skin->player_battle_row($this->player->username." gained a level!");
            $this->player->add_log($this->skin->lang_error->levelled_up);
        }

        if($this->type=="player") {
            if ($this->enemy->update_player()) {
                $attacks[] = $this->skin->enemy_battle_row($this->enemy->username." gained a level!");
                $this->enemy->add_log($this->skin->lang_error->levelled_up);
            } 
        }

        foreach ($attacks as $attack) {
            $battle_html .= $attack;
        }

        if ($this->player->victory) {
            $banner = $this->skin->success_box($this->skin->lang_error->you_won);
        } else if (!$banner) {
            $banner = $this->skin->error_box($this->skin->lang_error->you_lost);
        }

        $fight = $this->skin->fight($battle_html, $banner);

        return $this->return_value($attacks, $this->player->victory, $fight);
    }

   /**
   * setup enemy bits
   *
   * @return void;
   */
    public function player_combat_setup() {
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
        return;
    }


   /**
    * return relevant entry from selection
    *
    * @param array $array
    * @param bool $boolean
    * @param string $log
    * @return array
    * @return bool
    * @return string
    */
    public function return_value($array, $boolean, $log) {
        switch($this->type) {
            case 'array': 
                return $array; 
                break;
            case 'boolean': 
                return $boolean; 
                break;
            case 'player': 
                return $this->player; 
                break;
            case 'log':
            default: 
                return $log; 
                break;
        }
    }

   /**
   * spawn enemy
   *
   * @return object enemy;
   */
    public function create_enemy() {
        return new entity_enemy;
    }
 
}

/**
* enemy.class.php
*
* placeholder allows enemies to be spawned quickly
* @package code_common
* @author grego
*/
class entity_enemy {
   public $hp=0;
   public $username='Enemy';
   public $level=1;
   public $strength=0;
   public $vitality=0;
   public $agility=0;
   function add_log() {return;}
}
?>