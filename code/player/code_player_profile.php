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
    */
    public function make_player() {
        $return_value = parent::make_player("profiles");
        if ($this->is_member) {
            $this->custom_fields();
        }
        return $return_value;
    }

   /**
    * add some custom fields to the record
    * 
    */
    public function custom_fields() {
            if (!isset($this->player_id)) {
                foreach (json_decode($this->settings['custom_fields'], true) as $field => $default) {
                    $profile_string[$field] = $default;
                }
                
                $profile_data['player_id'] = $this->id;
                $profile_data['profile_string'] = json_encode($profile_string);
                $this->db->AutoExecute('profiles', $profile_data, 'INSERT');

                foreach ($profile_data as $name => $value) {
                    $this->$name = $value;
                }
            }

            $profile_array = json_decode($this->profile_string, true);
            foreach (json_decode($this->settings['custom_fields'], true) as $field => $default) {
                $this->$field = $profile_array[$field];
            }
    }

   /**
    * adds custom fields
    *
    * @param string $identity id or name
    * @return bool success
    */
    public function get_player($identity) {
        $return_value = parent::get_player($identity, "profiles");
        $this->custom_fields();
        return $return_value;
    }

   /**
    * extra db insert stuff
    *
    * @param bool $just just do the profile?
    */
    public function update_player($just = false) {
        foreach (json_decode($this->settings['custom_fields']) as $field => $default) {
            if (isset($this->$field)) {
                $profile_array[$field] = $this->$field;
            } else {
                $profile_array[$field] = $default;
            }
        }
                
        $profile_update_query['profile_string'] = json_encode($profile_array);

        $this->db->AutoExecute('profiles', $profile_update_query, 'UPDATE', '`player_id`='.$this->id);
        if (!$just) {
            parent::update_player();
        }
    }

// A constant reminder that there are always more functions to add ;)
   /**
    * Retrieves the player's friends
    *
    * @return boolean success
    */
    function getfriends(){

        // This is a resource-expensive funciton, so let's put a circuit breaker in
        if(!empty($this->friends)) return true;

        $query = $this->db->execute("SELECT `p`.`id`, `p`.`username`, `j`.`profile_string`
    FROM `players` AS `p`
    LEFT JOIN `profiles` AS `j` ON `j`.`player_id` = `p`.`id`
    WHERE (`id` IN (SELECT f.id2
                   FROM friends AS f
                   WHERE (f.id1=? OR f.id2=?) and `accepted`=1)
    OR `id` IN (SELECT f.id1
                FROM friends AS f
                WHERE (f.id1=? OR f.id2=?) and `accepted`=1))
    AND NOT `id`=?",
                array_fill(0, 5, $this->id));

        if ($query->recordcount() == 0) {
            return false;
        } else {
            while($friend = $query->fetchrow()) {
                $custom_fields = json_decode($friend['profile_string'], true);
                $this->friends[($friend['id'])] = array('username'=>$friend['username'], 'avatar'=>$custom_fields['avatar']);
            }
            return true;
        }
    }

}
?>
