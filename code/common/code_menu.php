<?php
/**
 * Handles that lovely list that goes down the side of the page
 *
 * @author josh04
 * @package code_public
 */
class code_menu {

    public $db;
    public $skin;
    public $section = "public";
    public $page = "index";

   /**
    * main menu-making function. pretty bitchin'
    *
    * (TODO) check for the existence of a static function as a way to implement the mail counter fairly
    *
    * @return string html
    */
    public function make_menu() {
                
        $menu_query = $this->db->execute("SELECT * FROM `menu` WHERE `enabled`=1 ORDER BY `order` ASC");
        while ($menu_entry = $menu_query->fetchrow()) {
            if ($this->page == $menu_entry['page'] && $this->section == $menu_entry['section'] && $menu_entry['extra'] == "") {
                $menu_entries[$menu_entry['category']] .= $this->skin->current_menu_entry($menu_entry['label'], $menu_entry['section'], $menu_entry['page'], $menu_entry['extra']);
            } else if ($this->section == "public") {
                $menu_entries[$menu_entry['category']] .= $this->skin->public_menu_entry($menu_entry['label'], $menu_entry['page'], $menu_entry['extra']);
            } else {
                $menu_entries[$menu_entry['category']] .= $this->skin->menu_entry($menu_entry['label'], $menu_entry['section'], $menu_entry['page'], $menu_entry['extra']);
            }
        }
        
        foreach ($menu_entries as $category_name => $category_html) {
            $menu_html .= $this->skin->menu_category($category_name, $category_html);
        }
        
        $menu_wrap = $this->skin->menu_wrap($menu_html);

        return $menu_wrap;
    }

}
?>
