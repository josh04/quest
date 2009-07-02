<?php
/**
 * makes a page of stats grids
 *
 * @author josh04
 * @package skin_public
 */
class skin_ranks extends skin_common {
    
   /**
    * makes a table row for the ranks
    *
    * @param array $player player id, username and rank
    * @param string $rank name of rank
    * @return string html
    */
    public function rank_row($player, $rank) {
        $rank_row = "<tr><td style=\"width:50%;\"><a href=\"index.php?page=profile&id=".$player['id']."\">".$player['username']."</a></td>
                        <td>".$player[$rank]." ".$rank."</td></tr>";
        return $rank_row;
    }


   /**
    * makes a table around those rows
    *
    * @param string $rank_rows table rows
    * @param string $title table title
    * @return string html
    */
    public function make_table($rank_rows, $title) {

        $make_table = "<div class=\"explanation\">
                        <strong>".$title."</strong>
                        <table style=\"width:100%;\">".$rank_rows."
                        </table>
                        </div>";

        return $make_table;
    }

   /**
    * wraps the tables up
    *
    * @param string $make_tables table html
    * @return string html
    */
    public function make_ranks($make_tables) {
        $make_ranks = "<h2>Player Ranks</h2>
                        <strong>Notice Board:</strong><br />
                        <i>This notice board contains statistics of all the agents on Campus.
                        See who's performing the best and who's not doing so well and see if
                        you can feature on the top rankings!</i>
                        ".$make_tables;
        return $make_ranks;
    }

}
?>
