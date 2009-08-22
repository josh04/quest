<?php
/**
 * Description of code_player_profile,php
 *
 * @author josh04
 * @package code_player
 */
class code_player_profile extends code_player {

   /**
    * accesses the "profiles" table.
    *
    * @param array $player_db the database what pull
    */
    protected function player_db_to_object($player_db) {
        $profile_query = $this->db->execute("SELECT * FROM `profiles` WHERE `player_id`=?", array($player_db['id']));
        
        if (!$profile_data = $profile_query->fetchrow()) {
            foreach (json_decode($this->settings['custom_fields'], true) as $field => $default) {
                $profile_string[$field] = $default;
            }
            $profile_data['player_id'] = $player_db['id'];
            $profile_data['profile_string'] = json_encode($profile_string);
            $this->db->AutoExecute('profiles', $profile_data, 'INSERT');
        }
        
        $profile_array = json_decode($profile_data['profile_string'], true);
        foreach (json_decode($this->settings['custom_fields'], true) as $field => $default) {
            $player_db[$field] = $profile_array[$field];
        }

        parent::player_db_to_object($player_db);
    }

   /**
    * extra db insert stuff
    */
    public function update_player() {
        foreach (json_decode($this->settings['custom_fields']) as $field => $default) {
            $profile_array[$field] = $this->$field;
        }
                
        $profile_update_query['profile_string'] = json_encode($profile_array);

        $this->db->AutoExecute('profiles', $profile_update_query, 'UPDATE', '`player_id`='.$this->id);
        
        parent::update_player();
    }

}
?>
