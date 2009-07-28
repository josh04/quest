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

   /**
    * decides what to do
    *
    * @return string html
    */
    public function edit_profile_switch() {
        switch($_GET['action']) {
            case 'update_profile':
                $edit_profile_switch = $this->update_profile(intval($_POST['id']));
                break;
            case 'update_password':
                $edit_profile_switch = $this->update_password(intval($_POST['id']));
                break;
            default:
                $edit_profile_switch = $this->edit_profile_page(intval($_GET['id']));
        }


        return $edit_profile_switch;
    }

   /**
    * Edit profile page for admins
    *
    * @param int $id player id
    * @param string $message Is there an error?
    * @return string html
    */
    public function edit_profile_page($id, $message="") {
        if (!$id) {
            $edit_profile_page = $this->skin->error_page($this->skin->lang_error->no_player_selected);
            return $edit_profile_page;
        }

        $profile = new code_player;
        $profile->get_player($id);

        $gender_list = $this->skin->gender_list($profile->gender);
        $show_email = "";

        if ($profile->show_email) {
            $show_email = "checked='checked'";
        }

        $edit_profile = $this->skin->edit_profile($profile, $gender_list, $show_email, "section=admin&amp;", $message); // blurgh
        $edit_password = $this->skin->edit_password_admin($profile->id);
        return $edit_profile.$edit_password;
    }


   /**
    * updates the profile database
    * (TODO) This needs cleaning code, badly. Avatar could be _anything_, for christ's sake.
    *
    * gender, 0 = undecided, 1 = female, 2 = male.
    *
    * @param int $id player id
    * @return string html
    */
    public function update_profile($id) {

        if ($_POST['submit'] != "Submit") { //stops you wiping your profile with GET
            $update_profile = $this->edit_profile_page($id);
            return $update_profile;
        }

        if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
            $update_profile = $this->edit_profile_page($id, $this->skin->error_box($this->skin->lang_error->email_wrong_format));
            return $update_profile;
        }

        $this->player->email         = htmlentities($_POST['email'], ENT_QUOTES, 'utf-8');
        $this->player->description   = htmlentities($_POST['description'], ENT_QUOTEs, 'utf-8');
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
        $update_profile = $this->edit_profile_page($id, $this->skin->success_box($this->skin->lang_error->profile_updated));
        return $update_profile;
    }

   /**
    * updates the player's password
    * (TODO) clean password
    * 
    * @param int $id player id
    * @return string html
    */
    public function update_password($id) {

        if ($_POST['new_password'] == "" || $_POST['confirm_password'] == "") { //stops you wiping your profile with GET
            $update_password = $this->edit_profile_page($id, $this->skin->lang_error->no_password);
            return $update_password;
        }

        if ($_POST['submit'] != "Submit") { //stops you wiping your profile with GET
            $update_password = $this->edit_profile_page($id);
            return $update_password;
        }

        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $update_password = $this->edit_profile_page($id, $this->skin->lang_error->passwords_do_not_match);
            return $update_password;
        }

        $profile = new code_player;
        $profile->get_player($id);

        $update_password['password'] = md5($_POST['new_password'].$profile->login_salt);
        $password_query = $this->db->AutoExecute('players', $update_password, 'UPDATE', 'id = '.$profile->id);

        return $update_password;

    }
}
?>
