<?php
/**
 * rpg hook
 * 
 * does the work of what was formerly code_player_rpg
 *
 * @package code_hooks
 * @author josh04
 */
 
   /**
    * accesses the "profiles" table.
    *
    */
    function rpg_pre_make_player(&$player) {
        if ($player->do_rpg == true) {
            $player->joins[] = "rpg";
        }
    }
    
   /**
    * add some custom fields to the record
    *
    */
    function rpg_post_make_player(&$player) {
        if ($player->do_rpg == true && $player->is_member && (!isset($player->player_id))) {
            $rpg_array = array( 'strength' => 1,
                                'vitality' => 1,
                                'agility' => 1,

                                'hp' => 60,
                                'hp_max' => 60,
                                'exp' => 0,
                                'exp_max' => 50,
                                'energy' => 10,
                                'energy_max' => 10,

                                'kills' => 0,
                                'deaths' => 0,

                                'level' => 1,
                                'stat_points' => 3
                                );

            $rpg_array['player_id'] = $player->id;
            $player->db->AutoExecute('rpg', $rpg_array, 'INSERT');

            foreach ($rpg_array as $name => $value) {
                $player->$name = $value;
            }

            $player->hp_percent = intval(($player->hp / $player->hp_max) * 100);
            $player->exp_diff = ($player->exp - $player->exp_max);
        }
    }
   /**
    * extra db insert stuff
    *
    * @param bool $just just do the profile?
    */
    function rpg_update(&$player) {
        if ($player->do_rpg == true) {

            if ($player->exp > $player->exp_max) {
                $player->level++;
                $player->exp_max = $player->exp_max + ($player->level * 70) - 20;
                $player->stat_points = $player->stat_points + 3;
                $player->hp_max = $player->hp_max + rand(5,15) + intval($player->vitality / 2);
                $levelled_up = true;
            }

            $rpg_update_query = array(      'strength' => $player->strength,
                                            'vitality' => $player->vitality,
                                            'agility' => $player->agility,

                                            'hp' => $player->hp,
                                            'hp_max' => $player->hp_max,
                                            'exp' => $player->exp,
                                            'exp_max' => $player->exp_max,
                                            'energy' => $player->energy,
                                            'energy_max' => $player->energy_max,

                                            'kills' => $player->kills,
                                            'deaths' => $player->deaths,

                                            'level' => $player->level,
                                            'stat_points' => $player->stat_points
                                            );


            $player->db->AutoExecute('rpg', $rpg_update_query, 'UPDATE', '`player_id`='.$player->id);
        }
    }

   /**
    * make rpg values when we make the player, pls
    */
    function rpg_create(&$player) {
        if ($player->do_rpg == true) {

            $rpg_array['player_id'] = $player->id;
            $rpg_array = array( 'strength' => 1,
                                'vitality' => 1,
                                'agility' => 1,

                                'hp' => 60,
                                'hp_max' => 60,
                                'exp' => 0,
                                'exp_max' => 50,
                                'energy' => 10,
                                'energy_max' => 10,

                                'kills' => 0,
                                'deaths' => 0,

                                'level' => 1,
                                'stat_points' => 5
                                );

            $player->db->AutoExecute('rpg', $rpg_array, 'INSERT');
        }
    }

?>