<?php
/**
 * Handles that lovely list that goes down the side of the page
 *
 * Defines a process for hooking into the menu entry, static function <class_name>_menu
 * which takes &$menu as a parameter, returns a string to be appended to the menu
 * label, and can add to the class variables $top and $bottom for new-mail style fun
 * stuff.
 *
 * (TODO) Better explanation here, install functions elsewhere
 *
 * @author josh04
 * @package code_common
 */
class core_menu {
    public $section = "common";
    public $page = "menu";
    public $player_section = "public";
    public $player_page = "index";
    
   /**
    * @var string for remote "topping off" of the menu
    */
    public $top = "";
    
   /**
    * @var string for sticking things to the bottom of the menu
    */
    public $bottom = "";
    
   /**
    * @var bool For remote function button disabling
    */
    public $enabled;

   /**
    * menu maker
    *
    * @param core_player $player
    * @param string $section
    * @param page $page
    * @param array $pages array of possible pages
    * @param array $settings array of setting keys
    * @param array $config db config
    */
    public function __construct($settings = array(), $config = array()) {
        $this->settings =& $settings;
        $this->db =& code_database_wrapper::get_db($config);
    }

   /**
    * core menu loading doofer
    *
    * @param code_common $common page that's calling
    */
    public function load_core($common) {
        $this->player =& $common->player;
        $this->player_section = $common->section;
        $this->player_page = $common->page;
        $this->pages = $common->pages;

        $common->page_generation->section = $this->section;
        $common->page_generation->page = $this->page;
        $this->skin = $common->page_generation->make_skin("skin_menu"); //(TODO) core("skin")

        return $this;
    }

   /**
    * main menu-making function. pretty bitchin'
    *
    * (DONE) check for the existence of a static function as a way to implement the mail counter fairly
    *
    * @return string html
    */
    public function make_menu() {

        if ($this->player->is_member) {
            $is_guest = '0';
        } else {
            $is_guest = '1';
        }

        $menu_query = $this->db->execute("SELECT * FROM `menu` WHERE `enabled`=1 AND `guest`=? ORDER BY `order` ASC", array($is_guest));
        if ($menu_query->RecordCount() == 0) {
            return $this->skin->error_page($this->lang->no_menu_entries);
        }
        while ($menu_entry = $menu_query->fetchrow()) {
            $menu_entries[$menu_entry['category']] .= $this->make_menu_entry($menu_entry);
        }
        
        foreach ($menu_entries as $category_name => $category_html) {
            if ($category_html) {
                $menu_html .= $this->skin->menu_category($category_name, $category_html);
            }
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
    protected function make_menu_entry($menu_entry) {
        $this->enabled = true;

        $section_class = "_code_".$menu_entry['section'];
        $section_function = $section_class."_menu";

        if (file_exists("code/".$menu_entry['section']."/".$section_class.".php")) {
            require_once("code/".$menu_entry['section']."/".$section_class.".php");
            if (method_exists($section_class, $section_function)) {
                eval("\$menu_entry['label'] = ".$section_class."::".$section_function."(\$this, \$menu_entry['label']);");
            }
        }

        if ($menu_entry['function']) {
            $class = "code_".$this->pages[$menu_entry['section']][$menu_entry['page']];
            $function = "code_".$menu_entry['page']."_menu";
            if (file_exists("code/".$menu_entry['section']."/".$class.".php")) {
                require_once("code/".$menu_entry['section']."/".$class.".php"); // (Josh) iirc, require_once should cause no problems with conflicting classes. did you hit a bug?
            }
            if (method_exists($class, $function)) {
                eval("\$menu_entry['label'] = ".$class."::".$function."(\$this, \$menu_entry['label']);"); // Hmm, this is a dirty dirty hack. I blame PHP, personally.
            }
        }
        if ($this->enabled) {
            if ($this->player_page == $menu_entry['page'] && $this->player_section == $menu_entry['section'] && $menu_entry['extra'] == "") {
                $make_menu_entry = $this->skin->current_menu_entry($menu_entry['label'], $menu_entry['section'], $menu_entry['page'], $menu_entry['extra']);
            //} else if ($menu_entry['section'] == "public") {
            //    $make_menu_entry = $this->skin->public_menu_entry($menu_entry['label'], $menu_entry['page'], $menu_entry['extra']);
            } else {
                $make_menu_entry = $this->skin->menu_entry($menu_entry['label'], $menu_entry['section'], $menu_entry['page'], $menu_entry['extra']);
            }
        } else {
            $make_menu_entry = "";
        }
        return $make_menu_entry;
    }
    
   /**
    * adds a new menu entry
    *
    * @param string $label name of entry
    * @param string $category name of menu category
    * @param string $section name of section
    * @param string $page name of page
    * @param string $extra extra url additions
    * @param int $function extra menu function
    * @param int $enabled is it turned on?
    * @param int $guest is it for guests?
    * @return int new menu id.
    */
    public function add_menu_entry($label, $category, $section, $page, $extra, $function, $enabled, $guest) {

        $menu_insert['label'] = htmlentities($label, ENT_QUOTES, 'utf-8');
        $menu_insert['section'] = htmlentities($section, ENT_QUOTES, 'utf-8');
        $menu_insert['page'] = htmlentities($page, ENT_QUOTES, 'utf-8');
        $menu_insert['extra'] = htmlentities($extra, ENT_QUOTES, 'utf-8');
        $menu_insert['category'] = htmlentities($category, ENT_QUOTES, 'utf-8');
        $menu_insert['function'] = intval($function);
        $menu_insert['enabled'] = intval($enabled);
        $menu_insert['guest'] = intval($guest);
        
        $menu_order_query = $this->db->execute("SELECT * FROM `menu` WHERE `guest`=? ORDER BY `order` ASC", array($guest));
        
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

   /**
    * modify a menu entry
    *
    * @param int $id Which menu entry to change?
    * @param string $label What shall the name be?
    * @param string $category Which category?
    * @param string $section Which section?
    * @param string $page Which page?
    * @param string $extra Anything else?
    * @param int $function Is it special?
    * @param int $enabled Is it to be shown?
    * @param int $guest Can guests see it?
    * @return int menu id
    */
    public function modify_menu_entry($id, $label, $category, $section, $page, $extra, $function, $enabled, $guest) {
        $menu_update['id'] = intval($id);
        $menu_update['label'] = htmlentities($label, ENT_QUOTES, 'utf-8');
        $menu_update['section'] = htmlentities($section, ENT_QUOTES, 'utf-8');
        $menu_update['page'] = htmlentities($page, ENT_QUOTES, 'utf-8');
        $menu_update['extra'] = htmlentities($extra, ENT_QUOTES, 'utf-8');
        $menu_update['category'] = htmlentities($category, ENT_QUOTES, 'utf-8');
        $menu_update['function'] = intval($function);
        $menu_update['enabled'] = intval($enabled);
        $menu_update['guest'] = intval($guest);

        $this->db->AutoExecute("menu", $menu_update, "UPDATE", "id=".$menu_update['id']);

        return $menu_update['id'];
    }

}
?>
