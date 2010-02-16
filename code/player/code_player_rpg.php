<?php
/**
 * A simple rpg stats module
 *
 * @author josh04
 * @package code_player
 */
class code_player_rpg extends core_player {

   /**
    * accesses the "rpg" table.
    *
    */
    public function make_player() {
        $return_value = parent::make_player("rpg");
        
        if ($this->is_member) {
            
            if (!isset($this->player_id)) {
                $this->generate_rpg_values();
            }

            $this->hp_percent = intval(($this->hp / $this->hp_max) * 100);
            $this->exp_diff = ($this->exp - $this->exp_max);
        }
        return $return_value;
    }

   /**
    * tags 'make rpg entries' onto get_player
    *
    * @param string $identity player id or name
    * @param string $join table to join on
    * @return bool success
    */
    public function get_player($identity, $join = "") {
        $return_value = parent::get_player($identity, $join);

        if (!isset($this->player_id) && $return_value) {
            $this->generate_rpg_values();
        }

        $this->hp_percent = intval(($this->hp / $this->hp_max) * 100);
        $this->exp_diff = ($this->exp - $this->exp_max);

        return $return_value;
    }

   /**
    * generates default rpg values
    */
    protected function generate_rpg_values() {
        // DEFAULT RPG STATS
        $rpg_array = array(         'strength' => 1,
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

        $rpg_array['player_id'] = $this->id;
        $this->db->AutoExecute('rpg', $rpg_array, 'INSERT');

        foreach ($rpg_array as $name => $value) {
            $this->$name = $value;
        }
    }

   /**
    * extra db insert stuff
    *
    * @param bool $just just do the rpg?
    */
    public function update_player($just = false) {

        if ($this->exp > $this->exp_max) {
            $this->level++;
            $this->exp_max = $this->exp_max + ($this->level * 70) - 20;
            $this->stat_points = $this->stat_points + 3;
            $this->hp_max = $this->hp_max + rand(5,15) + intval($this->vitality / 2);
            $levelled_up = true;
        }

        $rpg_update_query = array(      'strength' => $this->strength,
                                        'vitality' => $this->vitality,
                                        'agility' => $this->agility,

                                        'hp' => $this->hp,
                                        'hp_max' => $this->hp_max,
                                        'exp' => $this->exp,
                                        'exp_max' => $this->exp_max,
                                        'energy' => $this->energy,
                                        'energy_max' => $this->energy_max,

                                        'kills' => $this->kills,
                                        'deaths' => $this->deaths,

                                        'level' => $this->level,
                                        'stat_points' => $this->stat_points
                                        );

        $this->db->AutoExecute('rpg', $rpg_update_query, 'UPDATE', '`player_id`='.$this->id);
        if (!$just) {
            parent::update_player();
        }
    }

   /**
    * fer makin' a player
    * (TODO) configuable defaults
    *
    * @param string $username what do they want to be called?
    * @param string $password secret word time!
    * @param string $email not sure why we keep this at all
    * @return int player id
    */
    public function create_player($username, $password, $email) {
        $rpg_array['player_id'] = parent::create_player($username, $password, $email);
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

        $this->db->AutoExecute('rpg', $rpg_array, 'INSERT');
        return $rpg_array['player_id'];

    }

}
?>
