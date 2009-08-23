<?php
/**
 * A simple rpg stats module
 *
 * @author josh04
 * @package code_player
 */
class code_player_rpg extends code_player {

   /**
    * accesses the "rpg" table.
    *
    */
    public function make_player() {
        $return_value = parent::make_player("rpg");
        
        if ($this->is_member) {
            
            if (!isset($this->player_id)) {

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


            $this->exp_percent = intval(($this->exp / $this->exp_max) * 100);
        }
        return $return_value;
    }

   /**
    * extra db insert stuff
    */
    public function update_player() {

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

        parent::update_player();
    }

   /**
    * notify the player
    *
    * @param string $message what to say
    * @return bool success
    */
    public function add_log($message) {
        $insert_log['player_id'] = $this->id;
        $insert_log['message'] = $message;
        $insert_log['time'] = time();
        $log_query = $this->db->AutoExecute('user_log', $insert_log, 'INSERT');

        if ($log_query) {
            return true;
        } else {
            return false;
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
