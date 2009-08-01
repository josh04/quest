<?php
/**
 * Description of code_pages.php
 *
 * @author josh04
 * @package code_admin
 */
class code_pages extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_pages");

        $code_pages = $this->pages_switch();

        parent::construct($code_pages);
    }

   /**
    * Function for deciding what is being done this page load.
    *
    * @return string html
    */
    public function pages_switch() {
        $pages_switch = $this->pages_list();
        return $pages_switch;
    }

   /**
    * Makes the main page-changing menu.
    * (TODO) very similar to the menu code. HMM.
    *
    * @param string $message error message
    * @return string html
    */
    public function pages_list($message="") {
        foreach ($this->pages as $section_name => $section_array) {
            foreach ($section_array as $redirect => $page) {
                $page_sections[$section_name] .= $this->skin->page_row($page, $redirect, $section_name);
            }
            $page_categories_html .= $this->skin->section_wrapper($section_name, $page_sections[$section_name]);
        }

        $pages_list = $this->skin->pages_wrapper($page_categories_html, $message);
        return $pages_list;
    }

}
?>
