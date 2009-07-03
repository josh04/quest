<?php
/**
 * Description of code_items
 *
 * @author josh04
 * @package code_admin
 */
class code_items extends _code_admin {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_items");

        $code_items = $this->items_switch();

        parent::construct($code_items);
    }

    public function items_switch() {
        $this->list_items();
    }

}
?>
