<?php
/**
 * code_common.class.php
 *
 * common page code - keep it simple, keep it safe, this will be called for most every page
 * @package code_common
 * @author josh04
 */

class code_common {

    public $db;
    public $skin;
    public $page_title = "Quest"; //(TODO) use this
    public $player;
    public $config;
    public $cron;
    public $section;
    public $page;
    public $settings = array();
    public $pages = array();

   /**
    * section name and page name
    * 
    * @param string $section name of the site chunk we're in
    * @param string $page name of our particular page
    * @param string $config config values
    */
    public function __construct($section = "", $page = "", $config = array()) {
        $this->section = $section;
        $this->page = $page;
        $this->config = $config;
        $this->db =& code_database_wrapper::get_db($this->config);
    }

   /**
    * ERROR!
    *
    * @param string $error error text
    */
    public function error_page($error) {

        if (!$this->skin) {
            $this->make_skin();
        }
        
        $output = $this->skin->start_header("Error", "default.css");

        $output .= $this->skin->error_page($error);

        $output .= $this->skin->footer();

        print $output;
        exit;
    }

   /**
    * builds the page header
    *
    * @return string html
    */
    public function start_header() {
        
        $css = $this->player->skin;

        if(!isset($this->player->skin)) {
            $css = "default.css";
        }

        $start_header = $this->skin->start_header($this->page_title, "default.css");
        return $start_header;
    }

   /**
    * builds the player menu
    *
    * @deprecated
    * @return string html
    */
    public function menu_player() {

        $admin = "";

        if ($this->player->rank == 'Admin') {
            $admin = $this->skin->menu_admin();
        }

        $menu_player = $this->skin->menu_player($this->player, $mail, $admin);
        return $menu_player;
    }

   /**
    * builds the guest menu
    *
    * @return string html
    */
    public function menu_guest() {
        $online_query = $this->db->execute("SELECT count(*) AS c FROM players WHERE (last_active > (".(time()-(60*15))."))");
        $online_count = $online_query->fetchrow();
        $money_query = $this->db->execute("SELECT sum(`gold`) AS g FROM players");
        $money_sum = $money_query->fetchrow();

        $menu_guest = $this->skin->menu_guest($online_count['c'], $money_sum['g']);
        return $menu_guest;
    }

   /**
    * constructs the player
    *
    */
    public function make_player() {
        // (DONE) player shirt and percentage code has moved
        // Get our player object
        $this->player = new code_player;
        $this->player->page = $this->page;
        //Is our player a member, or a guest?
        $allowed = $this->player->make_player();
        if (!$allowed) {
            $this->error_page($this->skin->lang_error->page_not_exist);
        }
    }

   /**
    * gets settings from table
    */
    public function make_settings() {
        $settings_query = $this->db->execute("SELECT * FROM `settings`");
        while ($setting = $settings_query->fetchrow()) {
            $this->settings[$setting['name']] = $setting['value'];
        }
        $this->page_title = $this->settings['name'];
    }

   /**
    * sets up the database.
    *
    * @return bool success
    */
    public function make_db() {
        $this->db =& code_database_wrapper::get_db($this->config);

        if (!$this->db->IsConnected()) {
            $this->error_page($this->skin->lang_error->failed_to_connect);
        }
    }

   /**
    * makes skin class - if the player has a skin, use that. needs skin selection code to be useful, tbqh
    * (DONE) skin code
    *
    * @param string $skin_name skin name
    */
    public function make_skin($skin_name = "") {
        require_once("skin/lang/en/lang_error.php"); // (TODO) language string abstraction
        if (file_exists("skin/".$this->section."/_skin_".$this->section.".php")) {
            require_once("skin/".$this->section."/_skin_".$this->section.".php");
        }
        if ($skin_name) {
            require_once("skin/".$this->section."/".$skin_name.".php"); // Get config values.
            if ($this->player->skin) {
                if (file_exists("skin/".$this->player->skin."/".$this->section."/".$this->player->skin."_".$skin_name.".php")) {
                    require_once("skin/".$this->player->skin."/".$this->section."/".$this->player->skin."_".$skin_name.".php");
                    $skin_class_name = $this->player->skin."_".$skin_name;
                } else {
                    $skin_class_name = $skin_name;
                }
            } else {
                $skin_class_name = $skin_name;
            }
            
            $this->skin = new $skin_class_name;
        } else {
            $this->skin = new skin_common;
        }

        $this->skin->lang_error = new lang_error;
    }

   /**
    * this happy function runs the code which assembles the side bar
    *
    * @return string html
    */
    public function make_menu() {
        require_once("code/common/code_menu.php");
        $menu = new code_menu($this->player, $this->section, $this->page, $this->pages);
        $menu->make_skin('skin_menu');
        $make_menu = $menu->make_menu();
        return $make_menu;
    }

   /**
    * is it that time again?
    *
    */
    public function cron() {
        require_once('code/common/code_cron.php');
        $this->cron = new code_cron;
        $this->cron->db =& $this->db;
        $this->cron->update();
    }

   /**
    * sets up db, player, etc. can't go in construct because that happens after the child class has run.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    */
    public function initiate($skin_name = "") {
        $this->make_db();
        $this->make_settings();
        $this->cron();
        $this->make_player();
        $this->make_skin($skin_name);
    }

   /**
    * pieces the site together. intended to be overriden by child class to generate $page.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    */
    public function construct($page) {
        $output = $this->start_header();
 
            $output .= $this->make_menu();
        

        $output .= $this->skin->glue($page);
        $output .= $this->skin->footer();

        print $output;

    }

    /**
     * parses bbcode to return html
     * @param string $code bbcode
     * @param boolean $full whether to fully parse
     * @return string html
     */
    public function bbparse($code, $full=false) {
        if($full) $code = nl2br(htmlentities($code));
        $match = array (
                "/\[b\](.*?)\[\/b\]/is",
                "/\[i\](.*?)\[\/i\]/is",
                "/\[u\](.*?)\[\/u\]/is",
                "/\[s\](.*?)\[\/s\]/is",
                "/\[url\](.*?)\[\/url\]/is",
                "/\[url=(.*?)\](.*?)\[\/url\]/is",
                "/\[img\](.*?)\[\/img\]/is",
                "/\[code\](.*?)\[\/code\]/is",
                "/\[colo(u)?r=([a-z]*?|#?[A-Fa-f0-9]*){3,6}\](.*?)\[\/colo(u)?r\]/is",
                );
        $replace = array (
                "<strong>$1</strong>",
                "<em>$1</em>",
                "<ins>$1</ins>",
                "<del>$1</del>",
                "<a href='$1' target='_blank'>$1</a>",
                "<a href='$1' target='_blank'>$2</a>",
                "<img src='$1' alt='[image]' />",
                "<pre>$1</pre>",
                "<span style='color:$2;'>$3</span>",
                );
        $code = preg_replace($match,$replace,$code);
        return $code;
    }

    /**
     * updates a specific setting
     *
     * @param string setting name
     * @param string setting value
     * @return boolean success
     */
    public function setting_update($name, $value) {
        // If no query needs to be executed, the update is complete!
        $setting_query = true;
        // If they're arrays, we're going cyclic
        if (is_array($name) && is_array($value)) {
            if (count($name)!=count($value)) {
                return false;
            }
            foreach ($name as $a=>$b) {
                if ($this->settings[($name[$a])] == $value[$a]) {
                    continue;
                }
                $setting_query = $this->db->execute("UPDATE `settings` SET `value`=? WHERE `name`=?",array($value[$a],$name[$a]));
                if (!$setting_query) {
                    return false;
                }
                $this->settings[($name[$a])] = $value[$a];
            }
        } else {
            $setting_query = $this->db->execute("UPDATE `settings` SET `value`=? WHERE `name`=?",array($value,$name));
            if($setting_query)  {
                $this->settings[$name] = $value;
                return $true;
            }
        }

        return false; // This will only occur if it screws up. I expect.
    }

    /**
     * looks nicer than creating classes
     * 
     * @return object code_fight
     */
    public function fight_init() {
        return new code_fight;
    }
}

?>
