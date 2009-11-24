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
    * Pieces the site together.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    * @return string html
    */
    public function finish_page($page) {
        if (!$this->ajax_mode) {
            $output = $this->page_generation->start_header();

            $this->core("menu");
            $output .= $this->menu->make_menu();

            if ($this->settings->get['database_report_error'] && $this->db->_output_buffer) {
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
    * performs a hook action
    *
    * @param string $hook name of the hook to be executed
    */
    public function do_hook($hook) {
        global $hooks;

        $mod = $hooks[$hook][0];
        $file = $hooks[$hook][1];
        $function = $hooks[$hook][2];

        if ($mod == "" || $file == "" || $function == "") {
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
