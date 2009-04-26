<?php
	
	if ($_GET['action'] == "search") {
		//Check in case somebody entered 0
		$_POST['fromlevel'] = ($_POST['fromlevel'] == 0)  ? ""  : $_POST['fromlevel'];
		$_POST['tolevel'] = ($_POST['tolevel'] == 0)      ? ""  : $_POST['tolevel'];
		
		//Construct query
		$query2 = "SELECT id, username, hp, maxhp, level
               FROM players
               WHERE id != ? ";
		$query2 .= ($_POST['username'] != "")  ? " AND username LIKE ? "   : "";
		$query2 .= ($_POST['fromlevel'] != "") ? " AND level >= ? "        : "";
		$query2 .= ($_POST['tolevel'] != "")   ? " AND level <= ? "        : "";
		$query2 .= ($_POST['alive'] == "1")    ? " AND hp > 0 "            : " AND hp = 0 ";
		$query2 .= "LIMIT 0,20";
		
		//Construct values array for adoDB
		$values = array();
		array_push($values, $player->id); //Make sure battle search doesn't show self.
		
		if ($_POST['username'] != "") {
			array_push($values, "%".trim($_POST['username'])."%"); //Add username value for search
		}
		
		//Add level range for search
		if ($_POST['fromlevel']) {
			array_push($values, intval($_POST['fromlevel']));
		}
		
		if ($_POST['tolevel']) {
			array_push($values, intval($_POST['tolevel']));
		}
		

		$players = $db->execute($query2, $values); //Search!

    $display->battle_search($players);
   } else {
    $display->battle_search();
	 }
?>
