<?php
/**
 * let's make magic happen
 *
 * @author josh04
 * @package code_install
 */
class _code_install extends code_common {

   /**
    * pieces the site together. intended to be overriden by child class to generate $page.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    */
    public function construct($page) {

        $output = $this->start_header();
        $output .= $page;
        $output .= $this->skin->footer();

        print $output;

    }

   /**
    * sets up db, player, etc. can't go in construct because that happens after the child class has run.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    */
    public function initiate($skin_name = "") {
        $this->make_skin($skin_name);
    }
}
?>
