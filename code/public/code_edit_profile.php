<?php
/**
 * edits profiles, public version
 *
 * @author josh04
 * @package code_public
 */
class code_edit_profile extends code_common {

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
    public function profile_switch() { //(TODO) {page}_switch should be common, i think
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
            $update_profile = $this->edit_profile_page("Email incorrect format.");
            return $update_profile;
        }

        $update_player = array( 'email'         => $_POST['email'],
                                'description'   => $_POST['description'],
                                'gender'        => intval($_POST['gender']),
                                'msn'           => $_POST['msn'],
                                'aim'           => $_POST['aim'],
                                'skype'         => $_POST['skype'],
                                'avatar'        => $_POST['avatar'],
                                'skin'          => $_POST['skin']   );
        if ($_POST['show_email'] == 'on') {
            $update_player['show_email'] = 1;
        } else {
            $update_player['show_email'] = 0;
        }
        $player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);
        $this->player->make_player();
        $update_profile = $this->edit_profile_page($this->skin->lang_error->profile_updated);
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

        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $update_password = $this->edit_profile_page($this->skin->lang_error->passwords_do_not_match);
            return $update_password;
        }
        
        if (sha1($_POST['current_password'].$this->player->salt) != $this->player->password) {
            $update_password = $this->edit_profile_page($this->skin->lang_error->password_wrong);
            return $update_password;
        }
        $update_password['password'] = sha1($_POST['new_password'].$this->player->salt);
        $password_query = $this->db->AutoExecute('players', $update_password, 'UPDATE', 'id = '.$this->player->id);
        $this->player->password = $update_password['password'];
        $hash = sha1($this->player->id.$this->player->password.$this->player->login_rand);
        $_SESSION['hash'] = $hash;
        setcookie("cookie_hash", $hash, mktime()+2592000);
        $update_password = $this->edit_profile_page($this->skin->lang_error->password_updated);
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
        $edit_profile_page = $this->skin->edit_profile_page($this->player, $gender_list, $show_email, $message);
        return $edit_profile_page;
    }

}
?>
