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
    public $page_title = "CHERUBQuest"; //(TODO) use this
    public $player;
    public $config;

   /**
    * ERROR!
    *
    * @param string $error error text
    */
    public function error_page($error) {
        $error_page = $this->skin->error_page($error);
        $output = $this->skin->start_header("Error", "default.css");

        $output .= $error_page;

        $output .= $this->skin->footer();

        print $output;
        exit;
    }

   /**
    * builds the page header
    * (TODO) mess of a skin code
    *
    * @return string html
    */
    public function start_header() {
        $q = $this->db->execute("SELECT cssfile FROM `skins` WHERE `id`=?",array($this->player->skin));
        $css = $q->fetchrow();
        $css = $css['cssfile'];

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

            $mail = skin_mail::mail_wrap_small($mail_rows);
        }

        $admin = "";

        if ($this->player->rank == 'admin') {
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
        $onlinecountq = $this->db->execute("SELECT count(*) AS c FROM players WHERE (last_active > (".(time()-(60*15))."))");
        $onlinecount = $onlinecountq->fetchrow();
        $moneyq = $this->db->execute("SELECT sum(`gold`) AS g FROM players");
        $money = $moneyq->fetchrow();

        $menu_guest = $this->skin->menu_guest($onlinecount['c'], $money['g']);
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
            $this->error_page("You lack the secure file access required to view this page.");
        }
    }

   /**
    * sets up the database.
    *
    */
    public function make_db() {
        global $ADODB_QUOTE_FIELDNAMES;
        // Set up the Database
        $this->db = &ADONewConnection('mysqli'); //Get our database object.
        //$this->db->debug = true;
        $status = $this->db->Connect(     $this->config['server'],
                                          $this->config['db_username'],
                                          $this->config['db_password'],
                                          $this->config['database']     );
        $ADODB_QUOTE_FIELDNAMES = 1;
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC); //Set to fetch associative arrays

    }

   /**
    * gets config info
    *
    * @return array config values
    */
    public function make_config() {
        require("config.php"); // Get config values.
        $this->config = $config;
        return $config;
    }

   /**
    * makes skin class
    *
    * @param string $skin_name skin name
    */
    public function make_skin($skin_name = "") {
        if ($skin_name) {
            require_once("skin/public/".$skin_name.".php"); // Get config values.
            $this->skin = new $skin_name;
        } else {
            $this->skin = new skin_common;
        }
    }

   /**
    * sets up db, player, etc. can't go in construct_page because that happens after the child class has run.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    */
    public function initiate($skin_name = "") {
        $this->make_config();
        $this->make_skin($skin_name);
        $this->make_db();
        $this->make_player();
    }

   /**
    * pieces the site together. intended to be overriden by child class to generate $page.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    */
    public function construct_page($page) {

        $output = $this->start_header();

        if ($this->player->is_member) {
            $output .= $this->menu_player();
        } else {
            $output .= $this->menu_guest();
        }

        $output .= $this->skin->glue();
        $output .= $page;
        $output .= $this->skin->footer();

        print $output;

    }

}

?>
