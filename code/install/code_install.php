<?php
/**
 * let's make magic happen
 *
 * @author josh04
 * @package code_install
 */
class code_install extends code_common {

   /**
    * pieces the site together. intended to be overriden by child class to generate $page.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    */
    public function construct_page($page) {

        $output = $this->start_header();
        $output .= $page;
        $output .= $this->skin->footer();

        print $output;

    }

   /**
    * makes skin class
    *
    * @param string $skin_name skin name
    */
    public function make_skin($skin_name = "") {
        require_once("skin/lang/lang_error.php");
        if ($skin_name) {
            require_once("skin/install/".$skin_name.".php"); // Get config values.
            $this->skin = new $skin_name;
        } else {
            $this->skin = new skin_common;
        }
        $this->skin->lang_error = new lang_error;
    }

   /**
    * sets up db, player, etc. can't go in construct_page because that happens after the child class has run.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    */
    public function initiate($skin_name = "") {
        $this->make_skin($skin_name);
    }
}
?>
