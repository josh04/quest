<?php
/**
 * Description of code_player_quest
 *
 * @author josh04
 * @package code_public
 */
class code_player_quest extends code_player_rpg {

   /**
    * locks the player out if they're on a quest
    *
    */
    public function quest_lock_check() {
        if ($player->quest) {
            return false;
        }
    }

}
?>
