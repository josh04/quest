<?php
/**
 * skin_fight.class.php
 *
 * Draws fight bits
 * @package skin_common
 * @author josh04
 */

class skin_fight {

    public $lang_error;

   /**
    * draws the fight - player's moves
    *
    * @param string $attack what happened?
    * @return string html
    */
    public function player_battle_row($attack) {
        $battle_row = "<div style='text-align:right;color:#00FF00'>".$attack."</div>";
        return $battle_row;
    }

   /**
    * draws the fight - enemies's moves
    *
    * @param string $attack what happened?
    * @return string html
    */
    public function enemy_battle_row($attack) {
        $battle_row = "<div style='text-align:left;color:#FF0000'>".$attack."</div>";
        return $battle_row;
    }

   /**
    * draws the fight - misc
    *
    * @param string $attack what happened?
    * @return string html
    */
    public function battle_row($attack) {
        $battle_row = $attack."<br />";
        return $battle_row;
    }

}
?>
