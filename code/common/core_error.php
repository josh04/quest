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
        set_error_handler(array($this, "error_handler"), E_ALL^E_NOTICE);
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
            try {
                require_once("code/common/code_common.php");
                $common = new code_common;
                $common->core('default_lang');
                $common->core('page_generation');
                $common->skin =& $common->page_generation->make_skin();
                if (isset($this->common->settings->get['name'])) {
                    $site_name = $this->common->settings->get['name'];
                } else {
                    $site_name = "Quest";
                }

                $output = $common->skin->start_header("Error", $site_name, "default.css");

                $output .= $common->skin->error_page($text." @ ".$file." line ".$line.".");

                $output .= $common->skin->footer();
            } catch (Exception $e) {
                print $id.$text.$file.$line;
                $output = "Error time!";
            }
            
        }

        print $output;
        die();
    }

}
?>
