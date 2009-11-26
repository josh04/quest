<?php
/**
 * Base class for modular design
 *
 * code_common is the base class for 90% of the other classes in the project -
 * it only handles pretty much universal concepts such as database connections,
 * the skin, the player etc - shouldn't be anything specific to one part.
 *
 * (TODO) general skin functions are kept in here, which is probably wrong.
 * nowhere else to keep them though :(
 *
 * @package code_common
 * @author josh04
 */

define('HK_CONCAT',0);

class code_common {

   /**
    * The database object. Runs queries.
    *
    * @var QuestDB
    */
    public $db;

   /**
    * The current set of skin templates.
    *
    * @var skin_common
    */
    public $skin;

   /**
    * The current player object.
    *
    * @var code_player
    */
    public $player;

   /**
    * The databate config details, from config.php
    *
    * @var array
    */
    public $config;

   /**
    * The cronometer!
    *
    * @var code_cron
    */
    public $cron;

   /**
    * The current section.
    *
    * @var string
    */
    public $section;

   /**
    * The current page.
    *
    * @var string
    */
    public $page;

   /**
    * The settings array from the database.
    *
    * @var array
    */
    public $settings = array();

   /**
    * This stores the setting object, with set function
    * // (TODO) combine settings array with setting object
    *
    * @var code_setting
    */
    public $setting;
   /**
    * The pages array from the database.
    *
    * @var array
    */
    public $pages = array();

   /**
    * The class stored in this variable will be loaded by make_player as the player object.
    *
    * @var string
    */
    public $player_class = "code_player";

   /**
    * Ajax mode disables skin dressing
    *
    * @var bool
    */
    public $ajax_mode = false;

   /**
    * The common constructor for every class which extends code_common.
    *
    * Accepts and sets the section name and the page name, and an optional
    * config array. Also invokes code_database_wrapper to get the database
    * object.
    * 
    * @param string $section name of the site chunk we're in
    * @param string $page name of our particular page
    * @param string $config config values
    */
    public function __construct($section = "", $page = "", $config = array()) {
        $this->section = $section;
        $this->page = $page;
        $this->config = $config;
        $this->db = code_database_wrapper::get_db($this->config);
    }

   /**
    * Manual database connection function.
    *
    * Connects to the database, if for whatever reason this failed when the
    * class was constructed.
    *
    * @param bool $skipError if set, the error message isn't displayed if the connection fails
    * @return void
    */
    public function make_db($skipError = false) {
        $this->db =& code_database_wrapper::get_db($this->config);

        if (!$this->db->IsConnected() && !$skipError) {
            if (!$this->skin) {
                $this->core("page_generation");
                $this->skin =& $this->page_generation->make_skin();
            }
            $this->page_generation->error_page($this->lang->failed_to_connect);
        }
    }

   /**
    * Groups together all the standard constructing functions.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    * @param string $override skin override
    */
    public function initiate($skin_name = "", $override = "") {
        $this->core("default_lang");
        $this->make_db();
        $this->core("settings");
        $this->core("cron");
        $this->core("player");
        $this->core("extra_lang");
        $this->core("page_generation");
        $this->skin =& $this->page_generation->make_skin($skin_name, $override = "");
    }

   /**
    * Main page function.
    *
    * Pieces the site together. Intended to be overriden by child class to
    * generate $page.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    */
    public function finish_page($page) {
        if (!$this->ajax_mode) {
            $output = $this->page_generation->start_header();

            $this->core("menu");
            $output .= $this->menu->make_menu();

            if ($this->settings['database_report_error'] && $this->db->_output_buffer) {
                $page = $this->skin->error_box($this->db->_output_buffer).$page;
            }
            $output .= $this->skin->glue($page);
            $output .= $this->skin->footer();
        } else {
            $output = $page;
        }
        return $output;
    }

    /**
     * parses bbcode to return html
     * (TODO) preg_replace is sloooooow
     *
     * @param string $code bbcode
     * @param boolean $full whether to fully parse
     * @return string html
     */
    public function bbparse($code, $full=false) {
        $code = htmlentities($code, ENT_QUOTES, 'utf-8', false); //things should be htmlentitised _before_ they go into the DB - you shouldn't do it twice
        if ($full) {
            $code = nl2br($code);
        }

        preg_match_all("/\[code\](.*?)\[\/code\]/is", $code, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $match[1] = str_replace("[", "&#91;", $match[1]);
            $code = str_replace($match[0], "<pre>" . $match[1] . "</pre>", $code);
        }

        $match = array (
                "/\[b\](.*?)\[\/b\]/is",
                "/\[i\](.*?)\[\/i\]/is",
                "/\[u\](.*?)\[\/u\]/is",
                "/\[s\](.*?)\[\/s\]/is",
                "/\[url\](.*?)\[\/url\]/is",
                "/\[url=(.*?)\](.*?)\[\/url\]/is",
                "/\[img\](.*?)\[\/img\]/is",
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
                "<span style='color:$2;'>$3</span>",
                );
        $code = preg_replace($match, $replace, $code);
        while($this->bbparse_quote($code)) continue; // a fake loop that drills through
        return $code;
    }
    /**
     * parses quotes in bbcode (looped for multiple quotes)
     *
     * @param string $code bbcode
     * @param boolean $full whether to fully parse
     * @return string html
     */
    public function bbparse_quote(&$code) {
        $match = array (
                "/\[quote=(.+?)\](.*?)\[\/quote\]/is",
                "/\[quote=?\](.*?)\[\/quote\]/is",
                );
        $replace = array (
                "<div class='quote-head'>Quote ($1)</div><div class='quote'>$2</div>",
                "<div class='quote-head'>Quote</div><div class='quote'>$1</div>",
                );
        $code = preg_replace($match, $replace, $code, -1, $count);
        return $count;
}

   /**
    * paginates. move to code_common?
    *
    * @param int $max total number of thingies
    * @param int $current current offset
    * @param int $per_page number per page
    * @return string html
    */
    public function paginate($max, $current, $per_page) {
        $num_pages = intval($max / $per_page);

        if ($max % $per_page) {
            $num_pages++; // plus one if over a round number
        }

        $current_page = intval($current/$per_page);

        $current_html = $this->skin->paginate_current($current_page, $current, $this->section, $this->page);

        $pagination = array( ($current_page - 2) => ($current - 2*$per_page),
            ($current_page - 1) => ($current - $per_page),
            ($current_page + 1) => ($current + $per_page),
            ($current_page + 2) => ($current + 2*$per_page));

        foreach ($pagination as $page_number => $page_start) {
            if ($page_number >= 0 && $num_pages > $page_number) {
                $paginate_links[$page_number] = $this->skin->paginate_link($page_number, $page_start, $this->section, $this->page);
            }
        }

        $paginate_links[$current_page] = $current_html;
        ksort($paginate_links);

        foreach ($paginate_links as $link) {
            $paginate .= $link;
        }

        $paginate_final = $this->skin->paginate_wrap($paginate);
        return $paginate_final;

    }

   /**
    * performs a hook action
    *
    * @param string $hook name of the hook to be executed
    */
    public function do_hook($hook, $method = HK_CONCAT) {
        global $hooks;
        if(!isset($hooks[$hook])) return false;
        foreach($hooks[$hook] as $h) {
            $return[] = do_hook_action($h);
        }
    }

   /**
    * performs a single function for a hook
    *
    * @param string $hook an array containing details of the hook we're doing
    */
    public function do_hook_action($hook) {

        $mod = $hook[0];
        $file = $hook[1];
        $function = $hook[2];

        if ($mod == "" || $file = "" || $function = "") {
            return false;
        }

        $include_path = "./hooks/".$mod."/".$file;

        if (!file_exists($include_path)) {
            return false;
        }

        // So we don't include anything that slipped outside the function into the page
        ob_start();
            include($include_path);
        ob_end_clean();

        return $function($this);
    }

   /**
    * Loads a core module, e.g player
    *
    * @param string $module name of the module to load
    * @return bool success
    */
    public function core($module) {
        // no expert, but I think unless you made a directory called "code_"
        // you'd have a hard time convincing this to load anything other than
        // a core module
        if (file_exists("code/common/code_".$module.".php")) { 
            require_once("code/common/code_".$module.".php");
        } else {
            return false;
        }
        
        $class_name = "code_".$module;
        
        $core_module = new $class_name($this->settings, $this->config);
        $success = $core_module->load_core(&$this);

        if ($success === false) {
            $this->page_generation->error_page($this->lang->page_not_exist);
        } else {
            $this->$module = &$success;
        }
        
        return $success;
    }

}
?>
