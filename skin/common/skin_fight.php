<?php
/**
 * skin_fight.class.php
 *
 * Draws fight bits
 * @package skin_common
 * @author josh04
 */

class skin_fight extends skin_common {

    public $lang_error;

   /**
    * 'we won!' message
    *
    * @param int $id loser id
    * @param string $username name of loser
    * @param int $gold_shift gold gained
    * @param int $exp_gain experience gained
    * @return string html
    */
    public function victory_log($loser_id, $loser_username, $gold_shift, $exp_gain) {
        $victory_log = "You fought <a href='index.php?page=profile&amp;id=".$loser_id."'>".$loser_username."</a> but you won!<br /> You gained ".$exp_gain." experience and ".$gold_shift." gold.";
        return $victory_log;
    }
    
   /**
    * 'we lose!' message
    * 
    * @param int $id victor id
    * @param string $username name of victor
    * @param int $gold_shift gold lost
    * @return string html
    */
    public function loss_log($victor_id, $victor_username, $gold_shift) {
        $loss_log = "You fought <a href='index.php?page=profile&amp;id=".$victor_id."'>".$victor_username."</a> and lost.<br /> You lost ".$gold_shift." gold.";
        return $loss_log;
    }

   /**
    * how boring
    *
    * @param int $opponent_id who'd we fight?
    * @param string $opponent_name name thyself!
    * @return string html
    */
    public function draw_log($opponent_id, $opponent_name) {
        $draw_log = "You fought <a href='index.php?page=profile&amp;id=".$opponent_id."'>".$opponent_name."</a> and drew.";
        return $draw_log;
    }

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

   /**
    * there was a fight
    *
    * @param string $battle_html what happened?
    * @param string $banner win/lose/draw?
    */
    public function fight($battle_html, $banner) {
        return $banner.$battle_html;
    }

}
?>
