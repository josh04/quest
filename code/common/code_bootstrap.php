<?php
/**
 * boot boot boot boots on the other foot now
 *
 * The bootstrap assembles all the bits needed to create our common page object,
 * which then takes the reigns. Chooses which section and page to load, sets up
 * the database connection, assuming we're installed.
 *
 * @author josh04
 * @package code_common
 */
class code_bootstrap {

    public $config;
    public $db;
    public static $sections;

   /**
    * Three lines, all the code.
    */
    public function bootstrap() {
        if (get_magic_quotes_gpc()) {
            $_POST = array_map(array($this, 'stripslashes_deep'), $_POST);
            $_GET = array_map(array($this, 'stripslashes_deep'), $_GET);
            $_COOKIE = array_map(array($this, 'stripslashes_deep'), $_COOKIE);
            $_REQUEST = array_map(array($this, 'stripslashes_deep'), $_REQUEST);
        }
        $this->page_setup();
        $page = $this->page->construct();
        print $this->page->finish_page($page);
    }

   /**
    * Strips slashes. Le sigh.
    *
    * @param string $value
    * @return string slashes stripped
    */
    public function stripslashes_deep($value) {
        if (is_array($value)) {
            $value = array_map(array($this, 'stripslashes_deep'), $value);
        } else {
            $value = stripslashes($value);
        }

        return $value;
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
    * admin? install? or something more sinister? decide which section and page we're heading too.
    *
    */
    public function page_setup() {
       /**
        * Default page and section
        * (TODO) make these customisable?
        */
        $section = "public";
        $page = "index";
        
        $this->make_config();


       /**
        * Gets sections, by reading the folder names from 'code'
        */
        $sections = code_bootstrap::get_sections();

        if ($this->config['default_section'] && in_array($this->config['default_section'], $sections)) {
            $section = $this->config['default_section'];
        }

        $test = strtolower($_GET['section']);

        if (in_array($test, $sections)) {
            $section = strtolower($_GET['section']);
        }

       /**
        * Check if we're meant to be running the installer. If not, proceed to
        * load up the database
        */
        if (!IS_INSTALLED) {
            $section = "install";
            $pages[$section] = array(   "index"             =>          "start",
                                        "database"          =>          "database",
                                        "user"              =>          "user",
                                        "upgrade_database"  =>          "upgrade_database"    );
        } else {
            $this->db =& code_database_wrapper::get_db($this->config); // this unassuming line creates the database

            if (!$this->db->IsConnected()) {
                $this->page = new code_common;
                $this->page->core("default_lang");
                $this->page->core("page_generation");
                $this->page->skin =& $this->page->page_generation->make_skin();
                $this->page->page_generation->error_page($this->page->lang->failed_to_connect);
            }
            
            $page_query = $this->db->execute("SELECT * FROM `pages`");
           
            while($page_row = $page_query->fetchrow()){
                foreach (explode(",", $page_row['redirect']) as $redirect) {
                    $pages[$page_row['section']][$redirect] = $page_row['name'];
                }
                if ($page_row['mod']) {
                    $mods[$page_row['section']][$page_row['name']] = $page_row['mod'];
                }
            }
        }

       /**
        * If the page exists, take it.
        */
        if ($pages[$section][$_GET['page']]) {
            $page = $_GET['page'];
        }

       /**
        * Else; shit the bed.
        */
        if (!$pages[$section][$page]) {
            $this->page = new code_common;
            $this->page->core("default_lang");
            $this->page->core("page_generation");
            $this->page->skin =& $this->page->page_generation->make_skin();
            $this->page->page_generation->error_page($this->page->lang->page_not_exist);
        }

        if (file_exists("code/".$section."/"."_code_".$section.".php")) {
         /**
          *If there is, for example, a _code_install which makes universal adjustments to code_common
          * then load it.
          */
            require_once("code/".$section."/"."_code_".$section.".php");
        }

       /**
        * The main code_whatever.php page is required here
        */
        require_once("code/".$section."/code_".$pages[$section][$page].".php");

       /**
        * Our delightful hook override system thingy! If there's a 'mod', load
        * that. Otherwise, take the default class name.
        */

        if (isset($mods[$section][$pages[$section][$page]])) {
            require_once("mods/".$mods[$section][$pages[$section][$page]].".php");
            $class_name = $mods[$section][$pages[$section][$page]];
        } else {
            $class_name = "code_".$pages[$section][$page];
        }

        $this->page = new $class_name($section, $page, $this->config);
        $this->page->pages = $pages;
    }

   /**
    * figure out which mods are installed
    * 
    * @return array $sections array of names of folders
    */
    public static function get_sections() {
        if (is_array(code_bootstrap::$sections)) {
            return code_bootstrap::$sections;
        } else {
            $folder = opendir("code");
            while ($file = readdir($folder)) {
                if (is_dir("code/".$file) && $file != ".." && $file != ".") {
                    $sections[] = $file;
                }
            }
            closedir($folder);
            code_bootstrap::$sections = $sections;
            return $sections;
        }
    }

}
?>