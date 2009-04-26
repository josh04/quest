<?php
// bank.php : Manages the money.
// Withdraws, deposits etc. 


switch($_GET['action']) {
	case "deposit":
    if (abs(intval($_POST['amount'])) > $player->gold) {
	   	$display->simple_page("<You do not have that much cash to deposit.","","bred");
	 } else {
	   $player->gold = $player->gold - abs(intval($_POST['amount']));
	   $player->bank = $player->bank + abs(intval($_POST['amount']));

		 $deposit = $db->execute("UPDATE players 
                              SET `bank`=?, `gold`=? 
                              WHERE `id`=?", 
                              array($player->bank, 
                                  $player->gold, 
                                  $player->id));
     ($deposit) ? "" : print "Error depositing money.";
		 $display->simple_page("You deposited your money into the bank.
                            You now have £".$player->gold." on you and £".$player->bank." in the bank.","","byellow");
	 }
	 break;
  case "withdraw":
	 if (abs(intval($_POST['amount'])) > $player->bank) {
		$display->simple_page("You do not have that much cash to withdraw.","","bred");
	 } else	{
	 	$player->gold = $player->gold + abs(intval($_POST['amount']));
	  $player->bank = $player->bank - abs(intval($_POST['amount']));
		$withdraw = $db->execute("UPDATE players
                              SET bank=?, gold=? 
                              WHERE id=?", 
                              array($player->bank, 
                                    $player->gold, 
                                    $player->id));
    ($withdraw) ? "" : print "Error withdrawing money.";
		$display->simple_page("You withdrew your money from the bank.
                            You now have £".$player->gold." on you and £".$player->bank." in the bank.","","byellow");
	 }
	 break;
  case "interest":
	 $interest_rate = intval($player->bank * 0.03);
	 if ($player->interest == 0) {
	  $player->gold = $player->gold + $interest_rate;
		$query = $db->execute("UPDATE players
                           SET interest=1, gold=? 
                           WHERE id=?", 
                           array($player->gold, 
                                 $player->id));
		$display->simple_page("You collected your interest.\n
                            You now have £".$player->gold." on you and £".$player->bank." in the bank.","","byellow");
	 } else {
		$display->simple_page("You can collect your interest again tomorrow.","","bred");
	 }
	 break;
	default:
	 $display->bank();
}
