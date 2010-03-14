<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of code_exception
 *
 * @author Josh
 */
class exception_fatal extends Exception {

   /**
    * fatal exception, shut shit down.
    */
    public function __construct ($message = "", $code = 0, $previous = NULL ) {

        $page = new code_common;

        $page->core("default_lang");
        $page->core("page_generation");
        $page->skin =& $page->page_generation->make_skin();

        if (isset($page->settings->get['name'])) {
            $site_name = $page->settings->get['name'];
        } else {
            $site_name = "Error";
        }

        $output = $page->skin->start_header("Error", $site_name);

        $output .= $page->skin->error_page($page->lang->$message);

        $output .= $page->skin->footer();

        print $output;
    }

}
?>
