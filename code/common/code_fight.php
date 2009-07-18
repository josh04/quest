<?php
/**
* fight.class.php
*
* allows fights and scuffles to take place
* @package code_common
* @author grego
*/
 
class code_fight {
 
  public $db;
  private $enemy;

   /**
   * do battle with another player or an NPC
   *
   * @param object $enemy all enemy details
   * @param array $options 'returnval'
   * @return success or failure
   */
    public function battle($enemy, $options=array()) {
        if(!isset($options['returnval'])) $options['returnval'] = "fulltext";

        $this->enemy = $enemy;
        $this->skin = new skin_common_battle;

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
        if(!$options['nosave']==true) {$this->db->execute("UPDATE `players` SET `hp`=? WHERE `id`=?",array($this->player->hp,$this->player->id));}
        if($options['returnval']=="fulltext") return $attacks;
        if($options['returnval']=="boolean") return ($this->player->victory?1:0);
        if($options['returnval']=="details") return $this->player;

    }
 
}
?>