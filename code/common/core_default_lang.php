<?php
/**
 * core language file
 *
 * @author josh04
 * @package code_common
 */
class core_default_lang {

   /**
    * gets us the default language file
    *
    * @param code_common $common the calling page
    * @return null
    */
    public function load_core($common) {
        require_once("skin/lang/en/lang_error.php"); // (TODO) language string abstraction
        $common->lang = new lang_error;
        return null;
    }

}
?>
