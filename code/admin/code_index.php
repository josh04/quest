<?php
/**
 * Administrator index page
 *
 * @author grego
 * @package code_admin
 */
class code_index extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index");

        $code_index = $this->index_wrapper();

        parent::construct($code_index);
    }

   /**
    * generate main admin index page
    *
    * @return string html
    */
    public function index_wrapper() {
        $index = $this->skin->index_wrapper();
        return $index;
    }

}
?>
