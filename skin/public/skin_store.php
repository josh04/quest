<?php
/**
 * Store html
 *
 * @author josh04
 * @package skin_public
 */
class skin_store extends skin_common {
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
    public function make_item($item) {
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
