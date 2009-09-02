<?php
/**
 * boot boot boot boots on the other foot now
 * handles stuff which needs to be done _before_ we know what we're doing.
 *
 * @author josh04
 * @package code_common
 */
class code_bootstrap {

    public $config;
    public $db;

   /**
    * Three lines, all the code.
    */
    public function bootstrap() {
        if (get_magic_quotes_gpc()) {


            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
            $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
        }
        $this->page_setup();
        $this->page->construct();
    }
    //unfin
    public static function stripslashes_deep($value) {
        if (is_array($value)) {
            $value = array_map('stripslashes_deep', $value);
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
        $section = "public";
        $page = "index";

        $sections = $this->get_sections();

        $test = strtolower($_GET['section']);

        if (in_array($test, $sections)) {
            $section = strtolower($_GET['section']);
        }

        if (!IS_INSTALLED) {
            $section = "install";
            $pages[$section] = array( "index"             =>          "start",
                            "database"          =>          "database",
                            "user"              =>          "user",
                            "upgrade_database"  =>          "upgrade_database"    );
        } else {
            $this->make_config();
            $this->db =& code_database_wrapper::get_db($this->config); // this unassuming line creates the database

            if (!$this->db->IsConnected()) {
                $this->page = new code_common;
                $this->page->initiate();
                $this->page->error_page($this->skin->lang_error->failed_to_connect);
            }
            
            $page_query = $this->db->execute("SELECT * FROM `pages`");
           
            while($page_row = $page_query->fetchrow()){
                foreach (explode(",", $page_row['redirect']) as $redirect) {
                    $pages[$page_row['section']][$redirect] = $page_row['name'];
                }
            }
        }

        if ($pages[$section][$_GET['page']]) {
            $page = $_GET['page'];
        }
        
        if (!$pages[$section][$page]) {
            $this->page = new code_common;
            $this->page->make_skin();
            $this->page->error_page($this->page->skin->lang_error->page_not_exist);
        }

        if (file_exists("code/".$section."/"."_code_".$section.".php")) {
         /**
          *If there is, for example, a _code_install which makes universal adjustments to code_common
          * then load it.
          */
            require("code/".$section."/"."_code_".$section.".php");
        }
        
        require_once("code/".$section."/code_".$pages[$section][$page].".php"); //Include whichever php file we want.
        $class_name = "code_".$pages[$section][$page];
        $this->page = new $class_name($section, $page, $this->config);
        $this->page->pages = $pages;
    }

   /**
    * figure out which mods are installed
    * 
    * @return array $sections array of names of folders
    */
    public function get_sections() {
        $sections = array_slice(scandir("code"), 2);
        return $sections;
    }

}
?>
