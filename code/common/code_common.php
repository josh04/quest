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

   /**
    * section name and page name
    * 
    * @param string $section name of the site chunk we're in
    * @param string $page name of our particular page
    */
    public function __construct($section = "", $page = "") {
        $this->section = $section;
        $this->page = $page;
    }

   /**
    * ERROR!
    *
    * @param string $error error text
    */
    public function error_page($error) {

        $this->make_skin();

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
    * @return string html
    */
    public function menu_player() {

        $mail = "";
        if ($this->player->unread) {
            require_once("skin/public/skin_mail.php");
            $mail_query = $this->db->execute(" SELECT m.*, p.username FROM mail AS m
                                            LEFT JOIN players AS p ON m.from=p.id
                                            WHERE m.to=? AND m.status=0
                                            ORDER BY m.time DESC", array($this->player->id));

            if ($mail_query->recordcount()) {
                while ($mail = $mail_query->fetchrow()) {
                    $mail_rows .= skin_mail::mail_row_small($mail['id'], $mail['username'], $mail['subject']);
                }
            }

            $mail = ($mail_rows) ? skin_mail::mail_wrap_small($mail_rows) : "";
        }

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
        // (TODO) player shirt and percentage code has moved
        // Get our player object
        $this->player = new code_player;
        $this->player->db =& $this->db;
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
    * (TODO) skin code
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
 
        if ($this->player->is_member) {
            $output .= $this->menu_player();
        } else {
            $output .= $this->menu_guest();
        }
 
        $output .= $this->skin->glue();
        $output .= $page;
        if(preg_match("/<h2>Error<\/h2>/",$page)) $output .= "</div></div>";
        $output .= $this->skin->footer();

        print $output;

    }

    /**
     * parses bbcode to return html
     * @param string bbcode
     * @return string html
     */
    public function bbparse($code) {
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

}

?>
