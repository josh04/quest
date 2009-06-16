<?php
/**
 * It's a shop!
 *
 * @author josh04
 * @package code_public
 */
class code_store extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_store");

        $code_store = $this->make_store("");

        parent::construct($code_store);
    }

   /**
    * prints stroe page
    *
    * @return string html
    */
    public function make_store($message) {

        if ($_GET['action'] == "buy") {
            $make_store = $this->buy();
            return $make_store;
        }

        if ($_GET['action'] == "sell") {
            $make_store = $this->sell();
            return $make_store;
        }

        $items_query = $this->db->execute("SELECT * FROM blueprints ORDER BY 'type' asc");
        
        if ($items_query->recordcount() == 0) {
            $make_store = $this->skin->make_store("", "There are currently no items in the store.");
            return $make_store;
        }

        while ($item = $items_query->fetchrow()) {
            $make_items .= $this->skin->make_item($item);
        }

        $make_store = $this->skin->make_store($make_items, $message);
        return $make_store;
    }

   /**
    * buy an item
    *
    * @return string html
    */
    public function buy() {
        if (!$_POST['id']) {
            $_GET['action'] = "";
            $buy = $this->make_store("No item selected.");
            return $buy;
        }

        //Select the item from the database
        $item_query = $this->db->execute("SELECT `id`, `name`, `price` FROM `blueprints` WHERE `id`=?", array(intval($_POST['id'])));

        //Invalid item (it doesn't exist)
        if ($item_query->recordcount() == 0) {
            $_GET['action'] = "";
            $buy = $this->make_store("No item selected.");
            return $buy;
        }

        $item = $item_query->fetchrow();

        if ($item['price'] > $this->player->gold) {
            $_GET['action'] = "";
            $buy = $this->make_store("You cannot afford that item.");
            return $buy;
        }
        $this->player->gold = $this->player->gold - $item['price'];
        $update_player['gold'] = $this->player->gold;
        $player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $insert_item['player_id'] = $this->player->id;
        $insert_item['item_id'] = $item['id'];
        $insert_item_query = $this->db->AutoExecute('items', $insert_item, 'INSERT');

        $buy = "Congratulations on your purchase of ".$item['name'];
        return $buy;
    }

   /**
    * sell an item
    *
    * @return string html
    */
    public function sell() {
        if (!$_POST['id']) {
            $_GET['action'] = "";
            $sell = $this->make_store("No item selected.");
            return $sell;
        }

        //Select the item from the database
        $item_query = $this->db->execute("SELECT items.id, blueprints.name, blueprints.price FROM `items` LEFT JOIN `blueprints` ON items.item_id=blueprints.id WHERE items.player_id=? and items.id=?", array($this->player->id, intval($_POST['id'])));

        //Invalid item (it doesn't exist)
        if ($item_query->recordcount() == 0) {
            $_GET['action'] = "";
            $sell = $this->make_store("No item selected.");
            return $sell;
        }



		$item = $item_query->fetchrow(); //Get item info
		$this->player->gold = $this->player->gold + floor($item['price']/2);

		//Delete item from database, add tokens to player's account
		$delete_query = $this->db->execute("DELETE FROM `items` WHERE `id`=?", array($item['id']));

        $update_player['gold'] = $this->player->gold;

		$player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

		$sell = "You have sold your ".$item['name'].". We'll look after it well.";
		return $sell;
    }

}
?>
