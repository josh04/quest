<?php
// battle.php : Bad things happen here.
// Can't be arsed sorting through all this. Hope it works.

		if (!$_GET['id'] && !$_POST['username']) {
			$display->simple_page("No player chosen.","","bred");
			exit;
		}
		
		//Otherwise, get player data:
    $enemy = new player;
    $enemy->db =& $db;
    if (!$enemy->get_user_by_id(intval($_GET['id'])) && !$enemy->get_user_by_name($_POST['username'])) {
			$display->simple_page("Player does not exist.","","bred");
			exit;
		}
		
		//Otherwise, check if agent has any health
		if ($enemy->hp == 0) {
			$display->simple_page("Player is currently incapacitated.","","bred");
			exit;
		}
		
		//Player cannot attack any more
		if ($player->energy == 0) {
			$display->simple_page("You have no energy to attack.","","bred");
			exit;
		}
		
		//Player is unconscious
		if ($player->hp == 0) {
			$display->simple_page("You are currently unconscious.","","bred");
			exit;
		}
		
		if ($enemy->id == $player->id) {
			$display->simple_page("You cannot attack yourself.","","bred");
			exit;
		}
		
		//Get agent's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", array($enemy->id));
		$enemy->atkbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armour' and items.status='equipped'", array($enemy->id));
		$enemy->defbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		
		//Get player's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
		$player->atkbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armour' and items.status='equipped'", array($player->id));
		$player->defbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		
		//Calculate some variables that will be used
		$enemy->strdiff = (($enemy->strength - $player->strength) > 0)?($enemy->strength - $player->strength):0;
		$enemy->vitdiff = (($enemy->vitality - $player->vitality) > 0)?($enemy->vitality - $player->vitality):0;
		$enemy->agidiff = (($enemy->agility - $player->agility) > 0)?($enemy->agility - $player->agility):0;
		$player->strdiff = (($player->strength - $enemy->strength) > 0)?($player->strength - $enemy->strength):0;
		$player->vitdiff = (($player->vitality - $enemy->vitality) > 0)?($player->vitality - $enemy->vitality):0;
		$player->agidiff = (($player->agility - $enemy->agility) > 0)?($player->agility - $enemy->agility):0;
		$totalstr = $enemy->strength + $player->strength;
		$totalvit = $enemy->vitality + $player->vitality;
		$totalagi = $enemy->agility + $player->agility;
		
		//Calculate the damage to be dealt by each player (dependent on strength and vitality)
		$enemy->maxdmg = (($enemy->strength * 2) + $enemy->atkbonus['effectiveness']) - ($player->defbonus['effectiveness']);
		$enemy->maxdmg = $enemy->maxdmg - intval($enemy->maxdmg * ($player->vitdiff / $totalvit));
		$enemy->maxdmg = ($enemy->maxdmg <= 2)?2:$enemy->maxdmg; //Set 2 as the minimum damage
		$enemy->mindmg = (($enemy->maxdmg - 4) < 1)?1:($enemy->maxdmg - 4); //Set a minimum damage range of maxdmg-4
		$player->maxdmg = (($player->strength * 2) + $player->atkbonus['effectiveness']) - ($enemy->defbonus['effectiveness']);
		$player->maxdmg = $player->maxdmg - intval($player->maxdmg * ($enemy->vitdiff / $totalvit));
		$player->maxdmg = ($player->maxdmg <= 2)?2:$player->maxdmg; //Set 2 as the minimum damage
		$player->mindmg = (($player->maxdmg - 4) < 1)?1:($player->maxdmg - 4); //Set a minimum damage range of maxdmg-4
		
		//Calculate battle 'combos' - how many times in a row a player can attack (dependent on agility)
		$enemy->combo = ceil($enemy->agility / $player->agility);
		$enemy->combo = ($enemy->combo > 3)?3:$enemy->combo;
		$player->combo = ceil($player->agility / $enemy->agility);
		$player->combo = ($player->combo > 3)?3:$player->combo;
		
		//Calculate the chance to miss opposing player
		$enemy->miss = intval(($player->agidiff / $totalagi) * 100);
		$enemy->miss = ($enemy->miss > 20)?20:$enemy->miss; //Maximum miss chance of 20% (possible to change in admin panel?)
		$enemy->miss = ($enemy->miss <= 5)?5:$enemy->miss; //Minimum miss chance of 5%
		$player->miss = intval(($enemy->agidiff / $totalagi) * 100);
		$player->miss = ($player->miss > 20)?20:$player->miss; //Maximum miss chance of 20%
		$player->miss = ($player->miss <= 5)?5:$player->miss; //Minimum miss chance of 5%
		
		
		$battlerounds = 50; //Maximum number of rounds/turns in the battle. Changed in admin panel?
		
		$output = ""; //Output message
		
		
		//While somebody is still awake, fight!
		while ($enemy->hp > 0 && $player->hp > 0 && $battlerounds > 0)
		{
			$attacking = ($player->agility >= $enemy->agility)?$player:$enemy;
			$defending = ($player->agility >= $enemy->agility)?$enemy:$player;
			
			for($i = 0;$i < $attacking->combo;$i++)
			{
				//Chance to miss?
				$misschance = intval(rand(0, 100));
				if ($misschance <= $attacking->miss)
				{
					$output .= $attacking->username . " tried to attack " . $defending->username . " but missed!<br />";
				}
				else
				{
					$damage = rand($attacking->mindmg, $attacking->maxdmg); //Calculate random damage				
					$defending->hp -= $damage;
					$output .= ($player->username == $defending->username)?"<font color=\"red\">":"<font color=\"green\">";
					$output .= $attacking->username . " attacks " . $defending->username . " for <b>" . $damage . "</b> damage! (";
					$output .= ($defending->hp > 0)?$defending->hp . " HP left":"Unconscious";
					$output .= ")<br />";
					$output .= "</font>";

					//Check if anybody is dead
					if ($defending->hp <= 0)
					{
						$player = ($player->agility >= $enemy->agility)?$attacking:$defending;
						$enemy = ($player->agility >= $enemy->agility)?$defending:$attacking;
						break 2; //Break out of the for and while loop, but not the switch structure
					}
				}
				$battlerounds--;
				if ($battlerounds <= 0)
				{
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			for($i = 0;$i < $defending->combo;$i++)
			{
				//Chance to miss?
				$misschance = intval(rand(0, 100));
				if ($misschance <= $defending->miss)
				{
					$output .= $defending->username . " tried to attack " . $attacking->username . " but missed!<br />";
				}
				else
				{
					$damage = rand($defending->mindmg, $defending->maxdmg); //Calculate random damage
					$attacking->hp -= $damage;
					$output .= ($player->username == $defending->username)?"<font color=\"green\">":"<font color=\"red\">";
					$output .= $defending->username . " attacks " . $attacking->username . " for <b>" . $damage . "</b> damage! (";
					$output .= ($attacking->hp > 0)?$attacking->hp . " HP left":"Unconscious";
					$output .= ")<br />";
					$output .= "</font>";

					//Check if anybody is dead
					if ($attacking->hp <= 0)
					{
						$player = ($player->agility >= $enemy->agility)?$attacking:$defending;
						$enemy = ($player->agility >= $enemy->agility)?$defending:$attacking;
						break 2; //Break out of the for and while loop, but not the switch structure
					}
				}
				$battlerounds--;
				if ($battlerounds <= 0)
				{
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			$player = ($player->agility >= $enemy->agility)?$attacking:$defending;
			$enemy = ($player->agility >= $enemy->agility)?$defending:$attacking;
		}
		
		if ($player->hp <= 0)
		{
			//Calculate losses
			$exploss1 = $player->level * 6;
			$exploss2 = (($player->level - $enemy->level) > 0)?($enemy->level - $player->level) * 4:0;
			$exploss = $exploss1 + $exploss2;
			$goldloss = intval(0.2 * $player->gold);
			$goldloss = intval(rand(1, $goldloss));
			
			$output .= "<br /><u>You were defeated by " . $enemy->username . "!</u><br />";
			$output .= "<br />You lost <b>" . $exploss . "</b> EXP and <b>" . $goldloss . "</b> tokens.";
			$exploss3 = (($player->exp - $exploss) <= 0)?0:$exploss;
			$goldloss2 = (($player->gold - $goldloss) <= 0)?0:$goldloss;
			//Update player (the loser)
			$query = $db->execute("update `players` set `energy`=?, `exp`=?, `gold`=?, `deaths`=?, `hp`=0 where `id`=?", array($player->energy - 1, $player->exp - $exploss3, $player->gold - $goldloss2, $player->deaths + 1, $player->id));
			
			//Update enemy (the winner)
			if ($exploss + $enemy->exp < $enemy->maxexp)
			{
				$query = $db->execute("update `players` set `exp`=?, `gold`=?, `kills`=?, `hp`=? where `id`=?", array($enemy->exp + $exploss, $enemy->gold + $goldloss, $enemy->kills + 1, $enemy->hp, $enemy->id));
				//Add log message for winner
				$logmsg = "You were attacked by <a href=\"index.php?page=profile&id=" . $player->username . "\">" . $player->username . "</a> but you won!<br />\nYou gained " . $exploss . " EXP and " . $goldloss . " gold.";
				addlog($enemy->id, $logmsg, $db);
			}
			else //Defender has gained a level! =)
			{
				$query = $db->execute("update `players` set `stat_points`=?, `level`=?, `maxexp`=?, `exp`=?, `gold`=?, `kills`=?, `hp`=?, `maxhp`=? where `id`=?", array($enemy->stat_points + 3, $enemy->level + 1, ($enemy->level+1) * 70 - 20, ($enemy->exp + $exploss) - $enemy->maxexp, $enemy->gold + $goldloss, $enemy->kills + 1, $enemy->maxhp + 30, $enemy->maxhp + 30, $enemy->id));
				//Add log message for winner
				$logmsg = "You were challenged by <a href=\"index.php?page=profile&id=" . $player->username . "\">" . $player->username . "</a> but you won!<br />\nYou gained a level and " . $goldloss . " gold.";
				addlog($enemy->id, $logmsg, $db);
			}
		}
		else if ($enemy->hp <= 0)
		{
			//Calculate losses
			$expwin1 = $enemy->level * 6;
			$expwin2 = (($player->level - $enemy->level) > 0)?$expwin1 - (($player->level - $enemy->level) * 3):$expwin1 + (($player->level - $enemy->level) * 3);
			$expwin2 = ($expwin2 <= 0)?1:$expwin2;
			$expwin3 = round(0.6 * $expwin2);
			$expwin = ceil(rand($expwin3, $expwin2));
			$goldwin = ceil(0.2 * $enemy->gold);
			$goldwin = intval(rand(1, $goldwin));
			$output .= "<br /><u>You defeated " . $enemy->username . "!</u><br />";
			$output .= "<br />You won <b>" . $expwin . "</b> EXP and <b>" . $goldwin . "</b> tokens.";
			
			if ($expwin + $player->exp >= $player->maxexp) //Player gained a level!
			{
				//Update player, gained a level
				$output .= "<br /><b>You leveled up!</b>";
				$newexp = $expwin + $player->exp - $player->maxexp;
				$query = $db->execute("update `players` set `stat_points`=?, `level`=?, `maxexp`=?, `maxhp`=?, `exp`=?, `gold`=?, `kills`=?, `hp`=?, `energy`=? where `id`=?", array($player->stat_points + 3, $player->level + 1, ($player->level+1) * 70 - 20, $player->maxhp + 30, $newexp, $player->gold + $goldwin, $player->kills + 1, $player->maxhp + 30, $player->energy - 1, $player->id));
			}
			else
			{
				//Update player
				$query = $db->execute("update `players` set `exp`=?, `gold`=?, `kills`=?, `hp`=?, `energy`=? where `id`=?", array($player->exp + $expwin, $player->gold + $goldwin, $player->kills + 1, $player->hp, $player->energy - 1, $player->id));
			}
			
			//Add log message
			$logmsg = "You were challenged by <a href=\"index.php?page=profile&id=" . $player->username . "\">" . $player->username . "</a> in the combat arena and you were defeated...";
			addlog($enemy->id, $logmsg, $db);
			//Update enemy (who was defeated)
			$query = $db->execute("update `players` set `hp`=0, `deaths`=? where `id`=?", array($enemy->deaths + 1, $enemy->id));
		}
		else
		{
			$output .= "<br /><u>Both of you were too tired to finish the battle! Nobody won...</u>";
			$query = $db->execute("update `players` set `hp`=?, `energy`=? where `id`=?", array($player->hp, $player->energy - 1, $player->id));
			$query = $db->execute("update `players` set `hp`=? where `id`=?", array($enemy->hp, $enemy->id));
			
			$logmsg = "You were challenged by <a href=\"index.php?page=profile&id=" . $player->username . "\">" . $player->username . "</a> but nobody won...";
			addlog($enemy->id, $logmsg, $db);
		}

		$display->battle($output);
?>
