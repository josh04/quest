<?php
/**
 * Handles that lovely list that goes down the side of the page
 *
 * Defines a process for hooking into the menu entry, static function <class_name>_menu
 * which takes &$menu as a parameter, returns a string to be appended to the menu
 * label, and can add to the class variables $top and $bottom for new-mail style fun
 * stuff.
 *
 * @author josh04
 * @package code_public
 */
class code_menu {

    public $db;
    public $skin;
    public $player;
    public $section = "public";
    public $page = "index";
    public $top = "";
    public $bottom = "";

   /**
    * main menu-making function. pretty bitchin'
    *
    * (DONE) check for the existence of a static function as a way to implement the mail counter fairly
    *
    * @return string html
    */
    public function make_menu() {
                
        $menu_query = $this->db->execute("SELECT * FROM `menu` WHERE `enabled`=1 ORDER BY `order` ASC");
        while ($menu_entry = $menu_query->fetchrow()) {
            $menu_entries[$menu_entry['category']] .= $this->make_menu_entry($menu_entry);
        }
        
        foreach ($menu_entries as $category_name => $category_html) {
            $menu_html .= $this->skin->menu_category($category_name, $category_html);
        }
        
        $menu_wrap = $this->skin->menu_wrap($menu_html, $this->top, $this->bottom);
        return $menu_wrap;
    }

   /**
    * decides which function to use to make a single menu entry
    *
    * @param array $menu_entry menu entry from the db
    * @return string html
    */
    private function make_menu_entry($menu_entry) {
        if ($menu_entry['function']) {
            $class = "code_".$menu_entry['page'];
            $function = "code_".$menu_entry['page']."_menu";
            require_once("code/".$menu_entry['section']."/code_".$menu_entry['page'].".php");
            if (method_exists($class, $function)) {
                eval("\$menu_entry['label'] .= ".$class."::".$function."(\$this);"); // Hmm, this is a dirty dirty hack. I blame PHP, personally.
            }
        }
        if ($this->page == $menu_entry['page'] && $this->section == $menu_entry['section'] && $menu_entry['extra'] == "") {
            $make_menu_entry = $this->skin->current_menu_entry($menu_entry['label'], $menu_entry['section'], $menu_entry['page'], $menu_entry['extra']);
        } else if ($menu_entry['section'] == "public") {
            $make_menu_entry = $this->skin->public_menu_entry($menu_entry['label'], $menu_entry['page'], $menu_entry['extra']);
        } else {
            $make_menu_entry = $this->skin->menu_entry($menu_entry['label'], $menu_entry['section'], $menu_entry['page'], $menu_entry['extra']);
        }
        return $make_menu_entry;
    }
    
   /**
    * adds a new menu entry
    *
    * @param string $label name of entry
    * @param string $section name of section
    * @param string $page name of page
    * @param string $extra extra url additions
    * @param string $category name of menu category
    * @param int $function extra menu function
    * @return int new menu id.
    */
    public function add_menu_entry($label, $section, $page, $extra, $category, $function) {
        $menu_insert['label'] = htmlentities($label, ENT_COMPAT, 'utf-8');
        $menu_insert['section'] = htmlentities($section, ENT_COMPAT, 'utf-8');
        $menu_insert['page'] = htmlentities($page, ENT_COMPAT, 'utf-8');
        $menu_insert['extra'] = htmlentities($extra, ENT_COMPAT, 'utf-8');
        $menu_insert['category'] = htmlentities($category, ENT_COMPAT, 'utf-8');
        $menu_insert['function'] = intval($function);
        $menu_insert['enabled'] = 1;
        
        $menu_order_query = $this->db->execute("SELECT * FROM `menu` ORDER BY `order` ASC");
        
        while ($menu_entry = $menu_order_query->fetchrow()) {
            if (!$menu_category_start_max_order[$menu_entry['category']]) {
                $menu_category_start_max_order[$menu_entry['category']] = $menu_entry['order'];
            }
            if ($menu_max_order[$menu_entry['category']] < $menu_entry['order']) {
                $menu_max_order[$menu_entry['category']] = $menu_entry['order'];
            }
        }
        
        if ($menu_max_order[$menu_insert['category']]) {
            $menu_insert['order'] = $menu_max_order[$menu_insert['category']] + 1;
        } else {
            $menu_insert['order'] = max($menu_category_start_max_order) + 1;
        }

        $menu_insert_query = $this->db->AutoExecute("menu", $menu_insert, "INSERT");
        return $this->db->Insert_Id();
        
    }

}
?>
