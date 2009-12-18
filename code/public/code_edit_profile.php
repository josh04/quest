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

        return $code_edit_profile;
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
            case 'update_avatar':
                $profile_switch = $this->update_avatar();
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

        $this->core("email");

        if (!$this->email->validate($_POST['email'])) {
            $update_profile = $this->edit_profile_page($this->skin->error_box($this->lang->email_wrong_format));
            return $update_profile;
        }

        foreach (json_decode($this->settings->get['custom_fields'],true) as $field => $default) {
            $this->player->$field = htmlentities($_POST[$field], ENT_QUOTES, 'utf-8');
        }

        $this->player->email         = $_POST['email'];
        $this->player->description   = htmlentities($_POST['description'], ENT_QUOTES, 'utf-8');
        $this->player->gender        = intval($_POST['gender']);
        $this->player->msn           = htmlentities($_POST['msn'], ENT_QUOTES, 'utf-8');
        $this->player->aim           = htmlentities($_POST['aim'], ENT_QUOTES, 'utf-8');
        $this->player->skype         = htmlentities($_POST['skype'], ENT_QUOTES, 'utf-8');
        $this->player->skin          = htmlentities($_POST['skin'], ENT_QUOTES, 'utf-8');

        if ($_POST['show_email'] == 'on') {
            $this->player->show_email = 1;
        } else {
            $this->player->show_email = 0;
        }
        
        $this->player->update_player();
        $update_profile = $this->edit_profile_page($this->skin->success_box($this->lang->profile_updated));
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
            $update_password = $this->edit_profile_page($this->lang->no_password);
            return $update_password;
        }

        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $update_password = $this->edit_profile_page($this->lang->passwords_do_not_match);
            return $update_password;
        }

        if (md5($_POST['current_password'].$this->player->login_salt) != $this->player->password) {
            $update_password = $this->edit_profile_page($this->lang->password_wrong);
            return $update_password;
        }

        $this->player->update_password($_POST['new_password']);
        $update_password = $this->edit_profile_page($this->skin->success_box($this->lang->password_updated));
        return $update_password;
        
    }

   /**
    * updates the user's avatar
    * 
    * @return string html
    */
    public function update_avatar() {
        $exists = false;
        switch($_POST['avatar_type']) {
            case 'gravatar':
               // Treat a gravatar like a submitted URL.
               $avatar_url = "http://www.gravatar.com/avatar/".md5($this->player->email)."?s=40";
               break;

            case 'url':
                if (!filter_var($_POST['avatar_url'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) && $_POST['avatar_url']) {
                    $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_wrong_format));
                    return $update_avatar;
                }
                $avatar_url = $_POST['avatar_url'];
                break;

            case 'library':
                // Treat a library image similarly to a submitted URL, but check first 'cos it's local
                $avatar_url = "images/library/".$_POST['avatar_library'];
                $exists = file_exists($_POST['avatar_url']);
                if (!$exists) {
                    $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_library_no));
                    return $update_avatar;
                }
                break;
            
            case 'upload':
                // Ultimately treat an upload like a submitted URL too. But upload it first.

                if (!empty($_FILES['avatar_upload']['error'])) {
                    $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_upload_no));
                    return $update_avatar;
                }

                // Put it where you want it!
                $target_path = "images/avatars/" . $this->player->id . ".jpg";
                if (move_uploaded_file($_FILES['avatar_upload']['tmp_name'], $target_path)) {
                    $avatar_url = $target_path;
                    $exists = file_exists($target_path);
                } else {
                    $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_upload_no));
                    return $update_avatar;
                }
                break;
        }
        // I'll take whatever you've got...
        if(isset($avatar_url)) {
            if (!$exists) {
                $exists = @get_headers($avatar_url);
            }
            if ($exists) {
                $this->player->avatar = $avatar_url;
                $this->player->update_player();
                $update_avatar = $this->edit_profile_page($this->skin->success_box($this->lang->avatar_updated));
            } else {
                $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_url_notfound));
            }

            return $update_avatar;
        }

        $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_submit_no));
        return $update_avatar;
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

        $current_avatar = $this->player->avatar;
        if ($_POST['avatar_url'] != "" && preg_match("#^https?://(?:[^<>*\"]+|[a-z0-9/\._\- !]+)$#iU", $_POST['avatar_url'])) {
            $current_avatar = $_POST['avatar_url'];
        }
        
        if ($this->settings->get['avatar_url']) {
            $avatar_options .= $this->skin->edit_avatar_url($current_avatar);
        }

        if ($this->settings->get['avatar_gravatar']) {
            $avatar_options .= $this->skin->edit_avatar_gravatar( $this->player->email );
        }

        if ($this->settings->get['avatar_upload'] && is_writeable('images/avatars')) {
            $avatar_options .= $this->skin->edit_avatar_upload();
        }

        if ($this->settings->get['avatar_library'] && is_writeable('images/library')) {
            $handle = opendir('images/library');
            while (false !== ($file = readdir($handle))) {
                if($file=="." || $file==".." || $file=="Thumbs.db") continue;
                $library .= "<option>${file}</option>";
            }
            $avatar_options .= $this->skin->edit_avatar_library( $library );
        }

        $edit_profile = $this->skin->edit_profile($this->player, $gender_list, $show_email, "", $message);
        $edit_avatar = $this->skin->edit_avatar($avatar_options);
        $edit_password = $this->skin->edit_password($this->player->id);
        return $edit_profile.$edit_avatar.$edit_password;
    }

}
?>
