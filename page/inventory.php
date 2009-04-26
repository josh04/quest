<?php
//Inventory.php : Displays and edits the inventory.
//Functions for equipping and unequipping. Real ugly shizzle in here : /

//Are we equipping/unequipping an item?
if ($_GET['action'] == "equip" && intval($_GET['id']) != 0) {
	$items = $db->execute("SELECT i.status, i.item_id, b.type FROM items AS i
	                       LEFT JOIN blueprint_items AS b
	                       ON i.item_id=b.id
                         WHERE i.id=? AND i.player_id=?", 
                         array(intval($_GET['id']), $player->id));
  ($items) ? "" : print "Error retrieving selected item.";
                         
	if ($items->recordcount() == 1) {
		$item = $items->fetchrow();
		switch($item['status']) {
			case 0:
				$unequip = $db->execute("SELECT i.id, b.type FROM items AS i 
                                LEFT JOIN blueprint_items AS b
                                ON i.item_id=b.id 
                                WHERE i.player_id=? 
                                AND i.status=1", 
                               array($player->id));
        while ($row = $unequip->fetchrow()) {
				  if ($item['type'] == $row['type']) {
				  	$unequipped = $db->execute("UPDATE items SET status=0
                                        WHERE id=?", 
                                       array($row['id']));
            ($unequipped) ? "" : print "Error unquipping previously equipped item.";
				  }
				}
				//Equip the selected item
				$equip = $db->execute("UPDATE items SET status=1
                               WHERE id=?", 
                              array(intval($_GET['id'])));
        ($equip) ? "" : print "Error equipping selected item.";
				break;
			case 1: //User wants to unequip item
				$unequip = $db->execute("UPDATE items SET status=0
                               where id=?", 
                              array(intval($_GET['id'])));
        ($unequip) ? "" : print "Error unequipping selected item.";
				break;
			default: //Set status to unequipped, in case the item had no status when it was inserted into db
        print "Error: Item corrupt in database.";
				break;
		}
	}
	header("Location: index.php?page=inventory");
}

$items = $db->execute("SELECT i.*, b.type, 
                       b.name, b.effectiveness, 
                       b.description 
                       FROM items AS i
                       LEFT JOIN blueprint_items AS b
                       ON i.item_id=b.id
                       WHERE i.player_id=? 
                       ORDER BY i.status DESC", 
                      array($player->id));
($items) ? "" : print "Error retrieving items.";

if ($items->recordcount() == 0) {
	$display->simple_page("You have no items.");
} else {
  $display->inventory($items);
}

?>
