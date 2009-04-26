<?php
// hospital.php : Heal yourself.
// Heal fully or heal partially.

if ($player->hp == $player->maxhp) {
	$display->simple_page("You are already at full health.", "font-style:italic;", "bred");
} else
  switch($_GET['action']) {
    case "fullheal":
	   $heal = $player->maxhp - $player->hp;
	   $cost = $heal * 1; //Replace 0 with variable from settings table/file
  	 if ($player->gold < $cost) {
			$display->simple_page("You don't have enough tokens to heal fully.", "font-style:italic;","bred");
		  } else {
		   $player->hp = $player->maxhp;
		   $player->gold = $player->gold - $cost;
			 $heal = $db->execute("update `players` 
                              set `gold`=?, `hp`=? 
                              where `id`=?", 
                              array($player->gold, 
                                    $player->maxhp, 
                                    $player->id));
       ($heal) ? "" : print "Error updating player's health.";
	 	$display->simple_page("You have been fully healed. Your health is now ".$player->hp.".", 
                          "font-style:italic;","byellow");
		}
		break;
	 case "heal":
    if ( $player->maxhp < intval($_POST['amount']) ){
		  $display->simple_page("That is beyond your maximum health.", "color:#FF0000;","bred");
    } elseif ($player->gold < intval($_POST['amount'])) {
			$display->simple_page("You don't have enough money to heal that much.", "color:#FF0000;","bred");
    } else {
      $player->hp = intval($_POST['amount']) + $player->hp;
      $player->gold = $player->gold  - intval($_POST['amount']);
			$query = $db->execute("UPDATE players SET hp=?, gold=? 
                             WHERE `id`=?", 
                             array($player->hp, 
                                   $player->gold, 
                                   $player->id ) );
      ($query) ? "" : print "Error updating player.";
      $display->simple_page("You have healed yourself by ".intval($_POST['amount'])." point(s). 
                              Your health is now ".$player->hp.".", "color:#53735E;","byellow");
     }
	   break;
	 default:
    $display->hospital();
  }
	
?>
