<?php
/**
 * Description of code_inventory
 *
 * @author josh04
 * @package code_public
 */
class code_inventory extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct_page() {
        $this->initiate("skin_inventory");

        $code_inventory = $this->make_inventory();

        parent::construct_page($code_inventory);
    }

   /**
    * make inventory page
    *
    * @return string html
    */
    public function make_inventory() {

        if ($_POST['action']) {
            $make_inventory = $this->equip_item();
            return $make_inventory;
        }

        $item_query = $this->db->execute("SELECT i.*, b.type,
                                b.name, b.effectiveness,
                                b.description
                                FROM items AS i
                                LEFT JOIN blueprint_items AS b
                                ON i.item_id=b.id
                                WHERE i.player_id=?
                                ORDER BY i.status DESC",
                                array($this->player->id));

        if ($item_query->recordcount() == 0) {
            $make_inventory = "You have no items.";
            return $make_inventory;
        }

        while ($item = $item_query->fetchrow()) {
            $equip = ($item['status']) ? "Unequip" : "Equip";
            $items_html .= $this->skin->item_row($item, $equip);
        }

        $make_inventory = $this->skin->make_inventory($items_html);
        return $make_inventory;
    }

   /**
    * equips and unequips
    *
    * @return string html
    */
    public function equip_item() {
        $item_query = $this->db->execute("SELECT i.id, i.status, i.item_id, b.type FROM items AS i
                                    LEFT JOIN blueprint_items AS b
                                    ON i.item_id=b.id
                                    WHERE i.id=? AND i.player_id=?",
                                    array(intval($_POST['id']), $this->player->id));

        if ($item_query->recordcount() == 0) {
            $_POST['action'] = "";
            $equip_item = $this->make_inventory();
            return $equip_item;
        }

        $item = $item_query->fetchrow();
        switch($item['status']) {
            case 0:
                $unequip_query = $this->db->execute("SELECT i.id, b.type FROM items AS i
                                                LEFT JOIN blueprint_items AS b
                                                ON i.item_id=b.id
                                                WHERE i.player_id=?
                                                AND i.status=1", array($this->player->id));
                while ($item_equipped = $unequip_query->fetchrow()) {
                    if ($item['type'] == $item_equipped['type']) {
                        $unequip_update_query = $this->db->AutoExecute("UPDATE items SET status=0 WHERE id=?", array($item_equipped['id']));
                    }
                }
                $update_item['status'] = 1;
                //Equip the selected item
                $equip_query = $this->db->AutoExecute('items', $update_item, 'UPDATE', 'id = '.intval($item['id']));
                print $this->db->ErrorMsg();
                break;
            case 1: //User wants to unequip item
                $update_item['status'] = 0;
                $unequip_query = $this->db->AutoExecute('items', $update_item, 'UPDATE', 'id = '.intval($item['id']));
                break;
        }
        $_POST['action'] = "";
        $equip_item = $this->make_inventory();
        return $equip_item;
    }

}
?>