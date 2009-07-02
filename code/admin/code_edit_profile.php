<?php
/**
 * Description of code_edit_profile
 *
 * @author josh04
 * @package code_admin
 */
class code_edit_profile extends _code_admin {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->section = "public"; // Cheating here because I want to use the public skin file skin_profile
        $this->initiate("skin_profile");

        $code_edit_profile = $this->edit_profile_switch();

        parent::construct($code_edit_profile);
    }

    public function edit_profile_switch() {
        if ($_GET['id']) {
            $edit_profile_switch = $this->edit_profile_page(intval($_GET['id']));
        } else {
            $edit_profile_switch = $this->skin->error_page($this->skin->lang_error->no_player_selected);
        }
        return $edit_profile_switch;
    }

    public function edit_profile_page($id) {

        $profile = new code_player;
        $profile->db =& $this->db;
        $profile->get_player_by_id($id);

        $gender_list = $this->skin->gender_list($profile->gender);
        $show_email = "";
        if ($profile->show_email) {
            $show_email = "checked='checked'";
        }
        $edit_profile = $this->skin->edit_profile($profile, $gender_list, $show_email, $message);
        $edit_password = $this->skin->edit_password();
        return $edit_profile.$edit_password;
    }

}
?>
