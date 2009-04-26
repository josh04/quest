<?php

switch($_GET['action'])
{
	case "buy":
		if (!$_GET['id']) //No item ID
		{
			$display->simple_page("No item selected.","","bred");
			exit;
		}
		
		//Select the item from the database
		$query = $db->execute("select `id`, `name`, `price` from `blueprint_items` where `id`=?", array($_GET['id']));
		
		//Invalid item (it doesn't exist)
		if ($query->recordcount() == 0)
		{
			$display->simple_page("No item selected.","","bred");
			exit;
		}
		
		$item = $query->fetchrow();
		if ($item['price'] > $player->gold)
		{
			$display->simple_page("You cannot afford that.","","bred");
			exit;
		}
		$player->gold = $player->gold - $item['price'];
		$query1 = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold, $player->id));
		$insert['player_id'] = $player->id;
		$insert['item_id'] = $item['id'];
		$query2 = $db->autoexecute('items', $insert, 'INSERT');
		if ($query1 && $query2) //If successful
		{
			$display->html_page("<div class=\"byellow\"><strong>Store Manager</strong><br />
<i>You have successfully purchased a brand new ".$item['name'].". Good choice, agent.</i></div>");
			exit;
		}
		break;
		
	case "sell":
		if (!$_GET['id']) //No item ID
		{
			$display->simple_page("No item selected.","","bred");
			exit;
		}
		
		//Select the item from the database
		$query = $db->execute("select items.id, blueprint_items.name, blueprint_items.price from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", array($player->id, $_GET['id']));
		
		//Either item doesn't exist, or item doesn't belong to user
		if ($query->recordcount() == 0)
		{
			$display->simple_page("No item selected.","","bred");
			exit;
		}
		
		$sell = $query->fetchrow(); //Get item info
		$player->gold = $player->gold + floor($sell['price']/2);
		//Delete item from database, add tokens to player's account
		$query = $db->execute("delete from `items` where `id`=?", array($sell['id']));
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold, $player->id));
		
		$display->html_page("<div class=\"byellow\"><strong>Store Manager</strong><br />
<i>You have sold your ".$sell['name'].". We'll look after it well.</i></div>");
		$display->simple_page("You have sold your ".$sell['name'].".","","byellow");
		break;
		
  default:
		//Construct query
		$items = $db->execute("SELECT * FROM blueprint_items");
      if($items){
			 $display->shop($items);
			} else {
        print "Error retrieving blueprint items.";
      }
		break;
}
?>
