<?php
/**
 * Description of core_extra_lang
 *
 * @package code_common
 * @author josh04
 */
class core_extra_lang {
    
    public $db;

   /**
    * constructor, gives us the db
    *
    * @param array $config db values
    */
    public function __construct($settings = array(), $config = array()) {
        $this->db = code_database_wrapper::get_db($config);
    }

   /**
    * Gets any customisation
    *
    * (TODO) Player language choice?
    *
    * @param code_common $common page that's calling
    * @return null
    */
    public function load_core($common) {
        $alternative_skin = "";

        if ($common->settings->get['default_skin']) {
            $alternative_skin = $common->settings->get['default_skin'];
        }

        if ($common->player->skin) {
            $alternative_skin = $common->player->skin;
        }

        if ($common->override_skin) {
            $alternative_skin = $common->override_skin;
        }

        if ($alternative_skin) {
            if (file_exists("skin/".$alternative_skin."/lang/en/".$alternative_skin."_lang_error.php")) {
               require_once("skin/".$alternative_skin."/lang/en/".$alternative_skin."_lang_error.php");
               $class_name = $alternative_skin."_lang_error";
               $common->lang = new $class_name;
            }
        }
        $lang_query = $this->db->execute("SELECT * FROM `lang`");
        while ($lang = $lang_query->fetchrow()) {
            $name = $lang['name'];
            if (isset($common->lang->$name)) {
                $common->lang->$name = $lang['override'];
            }
        }

        return null;
    }

}
?>
