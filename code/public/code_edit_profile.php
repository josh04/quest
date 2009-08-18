<?php
/**
 * edits profiles, public version
 *
 * @author josh04
 * @package code_public
 */
class code_edit_profile extends code_common {

    public $player_class = "code_player_profile";

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_profile");

        $code_edit_profile = $this->profile_switch();

        parent::construct($code_edit_profile);
    }

   /**
    * where to?
    *
    * @return string html
    */
    public function profile_switch() { //(DONE) {page}_switch should be common, i think
        switch($_GET['action']) {
            case 'update_profile':
                $profile_switch = $this->update_profile();
                break;
            case 'update_password':
                $profile_switch = $this->update_password();
                break;
            default:
                $profile_switch = $this->edit_profile_page();
        }
        return $profile_switch;
    }

   /**
    * updates the profile database
    * (TODO) This needs cleaning code, badly. Avatar could be _anything_, for christ's sake.
    *
    * gender, 0 = undecided, 1 = female, 2 = male.
    * 
    * @return string html
    */
    public function update_profile() {
        if ($_POST['submit'] != "Submit") { //stops you wiping your profile with GET
            $update_profile = $this->edit_profile_page("");
            return $update_profile;
        }
        if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
            $update_profile = $this->edit_profile_page($this->skin->error_box($this->skin->lang_error->email_wrong_format));
            return $update_profile;
        }

        if (!preg_match("#^https?://(?:[^<>*\"]+|[a-z0-9/\._\- !]+)$#iU", $_POST['avatar']) && $_POST['avatar']) {
            $update_profile = $this->edit_profile_page($this->skin->error_box($this->skin->lang_error->avatar_wrong_format));
            return $update_profile;
        }

        foreach (json_decode($this->settings['custom_fields'],true) as $field => $default) {
            $this->player->$field = htmlentities($_POST[$field], ENT_QUOTES, 'utf-8');
        }

        $this->player->email         = $_POST['email'];
        $this->player->description   = htmlentities($_POST['description'], ENT_QUOTES, 'utf-8');
        $this->player->gender        = intval($_POST['gender']);
        $this->player->msn           = htmlentities($_POST['msn'], ENT_QUOTES, 'utf-8');
        $this->player->aim           = htmlentities($_POST['aim'], ENT_QUOTES, 'utf-8');
        $this->player->skype         = htmlentities($_POST['skype'], ENT_QUOTES, 'utf-8');
        $this->player->avatar        = $_POST['avatar'];
        $this->player->skin          = htmlentities($_POST['skin'], ENT_QUOTES, 'utf-8');

        if ($_POST['show_email'] == 'on') {
            $this->player->show_email = 1;
        } else {
            $this->player->show_email = 0;
        }
        
        $this->player->update_player();
        $update_profile = $this->edit_profile_page($this->skin->success_box($this->skin->lang_error->profile_updated));
        return $update_profile;
    }

   /**
    * updates the player's password
    * (TODO) clean password
    *
    * @return string html
    */
    public function update_password() {
        if ($_POST['submit'] != "Submit") { //stops you wiping your profile with GET
            $update_password = $this->edit_profile_page("");
            return $update_password;
        }

        if ($_POST['new_password'] == "" || $_POST['confirm_password'] == "") { //stops you wiping your profile with GET
            $update_password = $this->edit_profile_page($this->skin->lang_error->no_password);
            return $update_password;
        }

        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $update_password = $this->edit_profile_page($this->skin->lang_error->passwords_do_not_match);
            return $update_password;
        }

        if (md5($_POST['current_password'].$this->player->login_salt) != $this->player->password) {
            $update_password = $this->edit_profile_page($this->skin->lang_error->password_wrong);
            return $update_password;
        }

        $this->player->update_password($_POST['new_password']);
        $update_password = $this->edit_profile_page($this->skin->success_box($this->skin->lang_error->password_updated));
        return $update_password;
        
    }

   /**
    * diaplays the edit page
    *
    * @return string html
    */
    public function edit_profile_page($message = "") {
        $gender_list = $this->skin->gender_list($this->player->gender);
        $show_email = "";
        if ($this->player->show_email) {
            $show_email = "checked='checked'";
        }
        $edit_profile = $this->skin->edit_profile($this->player, $gender_list, $show_email, "", $message);
        $edit_password = $this->skin->edit_password($this->player->id);
        return $edit_profile.$edit_password;
    }

}
?>
