<?php
/**
 * Handles that lovely list that goes down the side of the page
 *
 * @author josh04
 * @package code_public
 */
class code_menu {

    public $current = "home";

    public function make_menu() {
        $menu_query = $this->db->execute("SELECT * FROM `menu` WHERE `enabled`=1");
        while ($menu_entry = $menu_query->fetchrow()) {

        }
    }

}
?>
