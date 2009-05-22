<?php
/**
 * start our intrepid install script
 *
 * @author josh04
 * @package code_install
 */
class code_start extends code_install {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct_page() {
        $this->initiate("skin_install");

        $code_start = $this->skin->start();

        parent::construct_page($code_start);
    }

}
?>
