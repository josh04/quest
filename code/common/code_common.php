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
 * (TODO) hook constants shouldn't really be defined here
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
    public $player_flags = array();

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

        $this->core("default_lang");
        $this->core("error");
        $this->db = code_database_wrapper::get_db($this->config);
    }

   /**
    * Groups together all the standard constructing functions.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    * @param string $override skin override
    */
    public function initiate($skin_name = "", $override = "") {
        $this->core("settings");
        $this->core("cron");
        $this->core("hooks");
        $this->core("player");
        $this->core("extra_lang");
        $this->core("page_generation");
        $this->skin =& $this->page_generation->make_skin($skin_name, $override = "");

        $allowed = $this->player->make_player();
        if (!$allowed) {
            $this->page_generation->error_page($this->lang->page_not_exist);
        }
        
        
    }

   /**
    * passes through to page_generation finish_page
    *
    * @param string $page page to be made
    * @return string html
    */
    public function finish_page($page) {
        if (!$this->page_generation->ajax_mode && isset($this->page_generation)) {
            $this->core("menu");
            $menu = $this->menu->make_menu();
            $output = $this->page_generation->finish_page($page, $menu);
        } else {
            $output = $page;
        }

        return $output;
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
        if (file_exists("code/common/core_".$module.".php")) {
            require_once("code/common/core_".$module.".php");
        } else {
            return false;
        }
        
        $class_name = "core_".$module;
        
        $core_module = new $class_name($this->settings, $this->config);
        
        $success = false;
        
        if (method_exists($core_module, "load_core")) {
            $success = $core_module->load_core(&$this);
        }
        
        if (!isset($this->$module->run_once)) {
            if ($success == false) {
                $this->$module = &$core_module;
                $this->$module->run_once = true;
            } else {
                $this->$module = &$success;
                $this->$module->run_once = true;
            }
            return $success;
        } else {
            if ($success == false) {
               return $core_module;
            } else {
               return $success;
            }
        }
    }

}
?>
