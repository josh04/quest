<?php
/**
 * start our intrepid install script
 *
 * @author josh04
 * @package code_install
 */
class code_start extends _code_install {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_install");

        if (is_writable("config.php") && is_writable("install.lock")) {
            $code_start = $this->skin->start();
        } else {
            $code_start = $this->skin->start_fail();
        }

        

        return $code_start;
    }

}
?>
