<?php
/**
 * Portal control page
 *
 * @author grego
 * @package code_admin
 */
class code_portal extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_portal");

        $code_index = $this->portal_wrapper();

        parent::construct($code_index);
    }

   /**
    * generate portal admin page
    *
    * @return string html
    */
    public function portal_wrapper() {

        if(count($_POST)) {
        $t = $this->setting_update(array('portal_name','portal_welcome','portal_description'),array(
                stripslashes($_POST['portal_name']),
                stripslashes($_POST['portal_welcome']),
                stripslashes($_POST['portal_description'])
        ));

        if($t) $message = $this->skin->success_box($this->skin->lang_error->changes_saved);
        else $message = $this->skin->error_box($this->skin->lang_error->changes_saved_no);
        }

        $index = $this->skin->portal_wrapper($this->settings['portal_name'],$this->settings['portal_welcome'],$this->settings['portal_links'],$message);
        return $index;
    }

}
?>
