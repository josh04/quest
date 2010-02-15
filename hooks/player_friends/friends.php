<?php
/**
 * player_friends hook
 * 
 * fishes the friends out of the db
 *
 * @package code_hooks
 * @author josh04
 */

// A constant reminder that there are always more functions to add ;)
   /**
    * Retrieves the player's friends
    *
    * @return boolean success
    */
    function get_friends(&$player) {

        // This is a resource-expensive funciton, so let's put a circuit breaker in
        if (!empty($player->friends)) {
            return true;
        }

        $query = $player->db->execute("SELECT `p`.`id`, `p`.`username`, `j`.`profile_string`
    FROM `players` AS `p`
    LEFT JOIN `profiles` AS `j` ON `j`.`player_id` = `p`.`id`
    WHERE (`id` IN (SELECT f.id2
                   FROM friends AS f
                   WHERE (f.id1=? OR f.id2=?) and `accepted`=1)
    OR `id` IN (SELECT f.id1
                FROM friends AS f
                WHERE (f.id1=? OR f.id2=?) and `accepted`=1))
    AND NOT `id`=?",
                array_fill(0, 5, $player->id));

        if ($query->recordcount() == 0) {
            return false;
        } else {
            while($friend = $query->fetchrow()) {
                $custom_fields = json_decode($friend['profile_string'], true);
                $player->friends[($friend['id'])] = array( 'id'=> $friend['id'], 'username' => $friend['username'], 'avatar' => $custom_fields['avatar']);
            }
            return true;
        }
    }

?>