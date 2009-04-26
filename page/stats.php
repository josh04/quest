<?php
//stat_points.php : Update stats.
//Players can spend their stat points here.

if ($_GET['spend'] && $player->stat_points > 0) {
	switch((intval($_GET['spend']))) {
		case '1':
		  $player->stat_points--;
		  $player->strength++;
			$query = $db->execute("UPDATE players set stat_points=?,
                             strength=? where id=?", 
                             array( $player->stat_points, 
                                    $player->strength, 
                                    $player->id));
			if ($query) {
        $display->simple_page("You have increased your strength.
                               It is now at " . $player->strength . ".");
			} else {
        print "Error updating player's strength.";
			}
			break;
		case '2':
		  $player->stat_points--;
		  $player->vitality++;
			$query = $db->execute("UPDATE players set stat_points=?,
                             vitality=? where id=?", 
                             array( $player->stat_points, 
                                    $player->vitality, 
                                    $player->id));
			if ($query) {
        $display->simple_page("You have increased your vitality.
                               It is now at " . $player->vitality . ".");
			} else {
				print "Error updating player's vitality.";
			}
			break;
		case '3':
		  $player->stat_points--;
		  $player->agility++;
			$query = $db->execute("UPDATE players set stat_points=?,
                             agility=? where id=?", 
                             array( $player->stat_points, 
                                    $player->agility, 
                                    $player->id));
			if ($query) {
        $display->simple_page("You have increased your agility.
                              It is now at " . $player->agility . ".");
			} else {
				print "Error updating player's agility.";
			}
			break;
	}
} else {
  $display->stats();
}

?>
