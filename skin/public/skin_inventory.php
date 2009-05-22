<?php
/**
 * inventory skin
 *
 * @author josh04
 * @package skin_public
 */
class skin_inventory extends skin_common {

   /**
    * main inventory page
    *
    * @param string $items_html items rows
    * @return string html
    */
    public function make_inventory($items_html) {
        $make_inventory = $items_html;
        return $make_inventory;
    }

   /**
    * makes and item row
    *
    * @param array $item item db object
    * @param string $equip to equip or to unequip
    * @return string html
    */
    public function item_row($item, $equip) {
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
}
?>
