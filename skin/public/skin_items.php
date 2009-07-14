<?php
/**
 * inventory and store skin
 *
 * @author josh04
 * @package skin_public
 */
class skin_items extends skin_common {

   /**
    * main inventory page
    *
    * @param string $items_html items rows
    * @param string $message error message
    * @return string html
    */
    public function make_inventory($items_html, $message = "") {
        $make_inventory = $message."<h2>Inventory</h2>".$items_html;
        return $make_inventory;
    }

   /**
    * makes and item row
    *
    * @param array $item item db object
    * @param string $equip to equip or to unequip
    * @return string html
    */
    public function inventory_item_row($item, $equip) {
	    $item_row .= "
                        <fieldset><legend>
                        <b>".$item['name']."</b></legend>
                        <table width='100%'>
                        <tr><td width='85%''>
                        ".$item['description']."<br />
                        <b>Effectiveness:</b> ".$item['effectiveness']."
                        </td><td width='15%'>
                        <form action='index.php?page=store&amp;action=sell' method='POST'>
                            <input type='hidden' name='id' value='".$item['id']."' />
                            <input type='submit' name='action' value='Sell' />
                        </form>
                        <form action='index.php?page=inventory&amp;action=equip' method='POST'>
                            <input type='hidden' name='id' value='".$item['id']."' />
                            <input type='submit' name='action' value='".$equip."' />
                        </form>
                        </td></tr>
                        </table>
                        </fieldset><br />";
        return $item_row;
    }

   /**
    * makes store page
    *
    * @param string $items
    * @param string $message
    * @return string html
    */
    public function make_store($items, $message) {
        $make_store = "<h2>Store</h2>".$message."<br />".$items;
        return $make_store;
    }

   /**
    * makes a single item box
    *
    * @param array $item item details
    * @return string html
    */
    public function store_item_row($item) {
        $make_item = "<fieldset>
            <legend><b>" . $item['name'] . "</b></legend>
            <table width='100%'>
            <tr><td width='85%'>
            ".$item['description'] . "<br />
            <b>Effectiveness:</b> ".$item['effectiveness']."
            </td><td width='15%'>
            <b>Price:</b> ".$item['price']."<br />
            <form action='index.php?page=store&amp;action=buy' method='POST'>
            <input type='hidden' name='id' value='".$item['id']."' />
            <input type='submit' value='Buy' /><br />
            </form>
            </td></tr>
            </table></fieldset><br />";
        return $make_item;
    }
}
?>
