<?php
/**
 * Description of code_player_facebook.php
 *
 * @author josh04
 * @package code_player
 */
class code_player_facebook extends code_common {

   /**
    * Facebook user-making code?
    *
    * @param string $join table to get extra data from
    * @return bool good to go?
    */
    public function make_player(&$facebook) {
        $this->id = $facebook->require_login();
        $this->is_facebook_user = true;
        $this->is_member = true;
        return $this->id;
    }

}
?>
