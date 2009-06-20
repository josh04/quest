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
        $this->make_config();
        $this->page_setup();
        $this->page->construct();
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

        if (!IS_INSTALLED) {
            $section = "install";
            require("code/install/code_install.php");
            $pages = array( "index"             =>          "start",
                            "database"          =>          "database",
                            "user"              =>          "user",
                            "upgrade_database"  =>          "upgrade_database"    );
        } else {
            $this->db =& code_database_wrapper::get_db($this->config); // this unassuming line creates the database

            if (!$this->db->IsConnected()) {
                $this->page = new code_common;
                $this->page->initiate();
                $this->page->error_page($this->skin->lang_error->failed_to_connect);
            }
            
            $page_query = $this->db->execute("SELECT * FROM `modules` WHERE `section`=?", array($section));
           
            while($page_row = $page_query->fetchrow()){
                $pages[$page_row['redirect']] = $page_row['name'];
            }
        }

        if ($pages[$_GET['page']]) {
            $page = $_GET['page'];
        }

        if (!$pages[$page]) {
            $this->page = new code_common;
            $this->page->initiate();
            $this->page->error_page($this->skin->lang_error->page_not_exist);
        }

        require_once("code/".$section."/code_".$pages[$page].".php"); //Include whichever php file we want.
        $class_name = "code_".$pages[$page];
        $this->page = new $class_name($section, $page);
        $this->page->config = $this->config;
    }

}
?>
