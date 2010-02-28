<?php
/**
 * player_profile hook
 * 
 * does the work of what was formerly code_player_profile
 *
 * @package code_hooks
 * @author josh04
 */

   /**
    * accesses the "profiles" table.
    *
    */
    function pre_make_player(&$player) {
        
        if ($player->do_profile == true) {
            $player->joins[] = "profiles";
        }
        
    }
    
   /**
    * add some custom fields to the record
    *
    */
    function post_make_player(&$player) {
        if ($player->do_profile == true) {
            if ($player->is_member) {
                if (!isset($player->profile_string)) {
                    foreach (json_decode($player->settings->get['custom_fields'], true) as $field => $default) {
                        $profile_string[$field] = $default;
                    }

                    $profile_data['player_id'] = $player->id;
                    $profile_data['profile_string'] = json_encode($profile_string);
                    $player->db->AutoExecute('profiles', $profile_data, 'INSERT');

                    foreach ($profile_data as $name => $value) {
                        $player->$name = $value;
                    }
                }

                $profile_array = json_decode($player->profile_string, true);
                
                foreach (json_decode($player->settings->get['custom_fields'], true) as $field => $default) {
                    $player->$field = $profile_array[$field];
                }
            }
        }
    }

   /**
    * extra db insert stuff
    *
    * @param bool $just just do the profile?
    */
    function update(&$player) {
        if ($player->do_profile == true) {
            foreach (json_decode($player->settings->get['custom_fields']) as $field => $default) {
                if (isset($player->$field)) {
                    $profile_array[$field] = $player->$field;
                } else {
                    $profile_array[$field] = $default;
                }
            }

            $profile_update_query['profile_string'] = json_encode($profile_array);

            $player->db->AutoExecute('profiles', $profile_update_query, 'UPDATE', '`player_id`='.$player->id);
        }
    }

?>