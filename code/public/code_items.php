<?php
/**
 * It's a shop! and now an inventory too ;)
 *
 * @author josh04
 * @package code_public
 */
class code_items extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_items");

        $code_store = $this->items_switch("");

        return $code_store;
    }

   /**
    * combined inv/store switch panel
    *
    * @return string html
    */
    public function items_switch() {
        switch($this->page) {
            case "store":
                if ($_GET['action'] == "buy") {
                    $make_store = $this->buy();
                    return $make_store;
                }

                if ($_GET['action'] == "sell") {
                    $make_store = $this->sell();
                    return $make_store;
                }

                $items_switch = $this->make_store();
                break;

            case "inventory":
                if ($_POST['action']) {
                    $make_inventory = $this->equip_item();
                    return $make_inventory;
                }

                $items_switch = $this->make_inventory();
                break;
            default:
                $this->error_page($this->lang->page_not_found);
        }

        return $items_switch;
    }

   /**
    * prints store page
    *
    * @param string $message error message
    * @return string html
    */
    public function make_store($message="") {



        $items_query = $this->db->execute("SELECT * FROM blueprints ORDER BY 'type' asc");
        
        if ($items_query->recordcount() == 0) {
            $make_store = $this->skin->make_store("", $this->skin->error_box($this->lang->no_items_in_store));
            return $make_store;
        }

        while ($item = $items_query->fetchrow()) {
            $make_items .= $this->skin->store_item_row($item);
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
            $buy = $this->make_store($this->skin->error_box($this->lang->no_item_selected));
            return $buy;
        }

        //Select the item from the database
        $item_query = $this->db->execute("SELECT `id`, `name`, `price` FROM `blueprints` WHERE `id`=?", array(intval($_POST['id'])));

        //Invalid item (it doesn't exist)
        if ($item_query->recordcount() == 0) {
            $_GET['action'] = "";
            $buy = $this->make_store($this->skin->error_box($this->lang->no_item_selected));
            return $buy;
        }

        $item = $item_query->fetchrow();

        if ($item['price'] > $this->player->gold) {
            $buy = $this->make_store($this->skin->error_box($this->lang->cannot_afford));
            return $buy;
        }
        $this->player->gold = $this->player->gold - $item['price'];
        $update_player['gold'] = $this->player->gold;
        $player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $insert_item['player_id'] = $this->player->id;
        $insert_item['item_id'] = $item['id'];
        $insert_item_query = $this->db->AutoExecute('items', $insert_item, 'INSERT');

        $buy = $this->make_store($this->skin->purchased($item['name']));
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
            $sell = $this->make_inventory($this->skin->error_box($this->lang->no_item_selected));
            return $sell;
        }

        //Select the item from the database
        $item_query = $this->db->execute("SELECT items.id, blueprints.name, blueprints.price FROM `items` LEFT JOIN `blueprints` ON items.item_id=blueprints.id WHERE items.player_id=? and items.id=?", array($this->player->id, intval($_POST['id'])));

        //Invalid item (it doesn't exist)
        if ($item_query->recordcount() == 0) {
            $_GET['action'] = "";
            $sell = $this->make_inventory($this->skin->error_box($this->lang->no_item_selected));
            return $sell;
        }



        $item = $item_query->fetchrow(); //Get item info
        $this->player->gold = $this->player->gold + floor($item['price']/2);

        //Delete item from database, add tokens to player's account
        $delete_query = $this->db->execute("DELETE FROM `items` WHERE `id`=?", array($item['id']));

        $update_player['gold'] = $this->player->gold;

        $player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $sell = $this->make_inventory($this->skin->sold($item['name']));
        return $sell;
    }

   /**
    * make inventory page
    *
    * @param string $message Error Message
    * @return string html
    */
    public function make_inventory($message = "") {

        $item_query = $this->db->execute("SELECT i.*, b.type,
                                b.name, b.effectiveness,
                                b.description
                                FROM items AS i
                                LEFT JOIN blueprints AS b
                                ON i.item_id=b.id
                                WHERE i.player_id=?
                                ORDER BY i.status DESC",
                                array($this->player->id));

        if ($item_query->recordcount() == 0) {
            $make_inventory = $this->skin->error_box($this->lang->no_items);
            return $make_inventory;
        }

        while ($item = $item_query->fetchrow()) {
            if ($item['status']) {
                $equip = $this->lang->unequip;
            } else {
                $equip = $this->lang->equip;
            }
            $items_html .= $this->skin->inventory_item_row($item, $equip);
        }

        $make_inventory = $this->skin->make_inventory($items_html, $message);
        return $make_inventory;
    }

   /**
    * equips and unequips
    *
    * @return string html
    */
    public function equip_item() {
        $item_query = $this->db->execute("SELECT i.id, i.status, i.item_id, b.type FROM items AS i
                                    LEFT JOIN blueprints AS b
                                    ON i.item_id=b.id
                                    WHERE i.id=? AND i.player_id=?",
                                    array(intval($_POST['id']), $this->player->id));

        if ($item_query->recordcount() == 0) {
            $equip_item = $this->make_inventory($this->skin->error_box($this->lang->item_not_found));
            return $equip_item;
        }

        $item = $item_query->fetchrow();
        if ($item['status']) {
            $update_item['status'] = 0;
            $unequip_query = $this->db->AutoExecute('items', $update_item, 'UPDATE', 'id = '.intval($item['id']));
            $message = $this->lang->item_unequipped;
        } else {
            $equipped_query = $this->db->execute("SELECT i.id, b.type FROM `items` AS i
                                            LEFT JOIN `blueprints` AS b
                                            ON i.item_id=b.id
                                            WHERE i.player_id=?
                                            AND i.status=1", array($this->player->id));
            while ($item_equipped = $equipped_query->fetchrow()) {
                if ($item['type'] == $item_equipped['type']) {
                    $unequip_item['status'] = 0;
                    $unequip_update_query = $this->db->AutoExecute("items", $unequip_item, "UPDATE", "id=".$item_equipped['id']);
                }
            }
            $update_item['status'] = 1;
            //Equip the selected item
            $equip_query = $this->db->AutoExecute('items', $update_item, 'UPDATE', 'id = '.intval($item['id']));
            $message = $this->lang->item_equipped;
        }
        
        $equip_item = $this->make_inventory($this->skin->success_box($message));
        return $equip_item;
    }


}
?>
