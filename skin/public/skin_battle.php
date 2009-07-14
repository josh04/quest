<?php
/**
 * fightin' 'n' stuff
 *
 * @author josh04
 * @package skin_public
 */
class skin_battle extends skin_common {

   /**
    * find a player.
    *
    * @return string html
    */
    public function battle_search_page($username, $level_min, $level_max) {
        $battle_search_page = "
            Search: 
            <form method='post' action='index.php?page=battle&amp;action=search'>
                Username:
                <input type='text' name='username' value='".$username."' />
                Level:
                <input type='text' name='level_min' size='4' value='".$level_min."' /> to <input type='text' name='level_max' size='4' value='".$level_max."' />
                Status:
                <select name='alive' size='2'>
                    <option value='1' selected='selected'>Conscious</option>
                    <option value='0'>Unconscious</option>
                </select>
                <input type='submit' value='Search' />
            </form>
            <br />
            
            Attack a player<br />
            <form method='post' action='index.php?page=battle&amp;action=fight'>
                Username: <input type='text' name='username' />
                <input type='submit' value='Battle' />
            </form>";
        return $battle_search_page;
    }

   /**
    * player search return row
    *
    * @param array $player player from db
    * @return string html
    */
    public function battle_search_row($player) {
        $battle_search_row = "
            <tr>
                <td>
                    <a href='index.php?page=profile&amp;id=".$player['id']."'>" .$player['username']."</a>
                </td>
                <td>
                    ".$player['level']."
                </td>
                <td>
                    <form method='POST' action='index.php?page=battle&amp;action=fight'>
                        <input type='hidden' name='id' value='".$player['id']."' />
                        <input type='submit' value='Attack' />
                    </form>
                </td>
            </tr>";

        return $battle_search_row;
    }
   /**
    * makes table
    *
    * @param string $player_rows player rows html
    * @return string html
    */
    public function battle_search_row_wrap($player_rows) {
        $battle_search_row_wrap = "<table>".$player_rows."</table>";
        return $battle_search_row_wrap;
    }

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
