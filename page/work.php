<?php
// work.php : Simple money making.
// Either update and spew a message, or give the spiel.

if ($player->energy == 0) {
	$display->simple_page("You have no energy left! You must rest a while.");
} elseif ($_GET['action'] == "work") {
  $player->energy--;
  $player->gold = $player->gold + 50 * $player->level;
	$work = $db->execute("UPDATE players 
                        SET energy=?, gold=? 
                        WHERE id=?", 
                        array($player->energy, 
                              $player->gold, 
                              $player->id));
  ($work) ? "" : "Error updating player.";
	$display->simple_page("After a long and tiring day, 
                        you have now finished working and now have £".$player->gold.".");
} else {
  $display->work();
}
?>
