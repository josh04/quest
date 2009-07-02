<?php
/**
 * Description of _code_admin
 *
 * @author josh04
 * @package code_public
 */
class _code_admin extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_");

        $_code_admin = ;

        parent::construct($_code_admin);
    }

}
?>
