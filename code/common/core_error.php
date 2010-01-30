<?php
/**
 * error handling time!
 *
 * @author josh04
 */
class core_error {

    private $common;

   /**
    * sets up custom error handling
    *
    * @param code_common $common calling page
    * @return core_error
    */
    public function load_core(&$common) {
        //set_error_handler(array($this, "error_handler"), E_ALL);
        $this->common =& $common;
        return $this;
    }



   /**
    * error handling function
    *
    * @param <type> $id
    * @param <type> $text
    * @param <type> $file
    * @param <type> $line
    */
    public function error_handler($id, $text, $file, $line) {
        
        if ($id == E_NOTICE) {
            return;
        }

        if (!isset($this->common->lang)) {
            $this->core("default_lang");
        }

        if (isset($this->common->page_generation)) {

            if (!$this->common->skin) {
                $this->common->page_generation->make_skin();
            }

            if (isset($this->common->settings->get['name'])) {
                $site_name = $this->common->settings->get['name'];
            } else {
                $site_name = "Quest";
            }

            $output = $this->common->skin->start_header("Error", $site_name, "default.css");

            $output .= $this->common->skin->error_page($text." @ ".$file." line ".$line.".");

            $output .= $this->common->skin->footer();
        } else {
            print $id.$text.$file.$line;
            $output = "Error time!";
        }

        print $output;
        die();
    }

}
?>
