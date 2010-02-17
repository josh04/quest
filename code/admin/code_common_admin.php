<?php
/**
 * extends code_common, verifies that the user in question has the auth to do this
 *
 * @author josh04
 * @package code_admin
 */
class code_common_admin extends code_common {

    public function inititate($skin_name) {
        parent::initiate($skin_name);

        if ($this->player->rank != "Admin") { // yeah, I know, poor implementation of permissions
            $this->page_generation->error_page($this->lang->access_denied);
        }
    }

    public static function code_common_admin_menu(&$menu, $label) {
        if ($menu->player->rank != "Admin") { // yeah, I know, poor implementation of permissions
            $menu->enabled = false;
        }
        return $label;
    }

}
?>
