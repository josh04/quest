<?php
/**
 * error handling time!
 *
 * @author josh04
 */
class code_error {
   /**
    * sets up custom error handling
    *
    * @param code_common $common calling page
    * @return code_error
    */
    public function load_core(&$common) {
        print 1;
        set_error_handler(array($common, "error_handler"));
        return $this;
    }
}
?>
