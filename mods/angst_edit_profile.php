<?php
/**
 * edits profiles, public version
 *
 * @author josh04
 * @package code_public
 */
class angst_edit_profile extends code_edit_profile {

    public $player_class = "code_player_profile";

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct($code_other = "") {
        if ($code_other) {
             parent::construct($code_other);
             return;
        }  
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
            case 'update_avatar':
                $profile_switch = $this->update_avatar();
                break;
            case 'twitter_auth':
                $profile_switch = $this->twitter_auth();
                break;
            case 'twitter_recount':
                $profile_switch = $this->twitter_recount();
                break;
            default:
                $profile_switch = $this->edit_profile_page();
        }
        return $profile_switch;
    }

   /**
    * got twitter? Check it
    *
    * @return string html
    */
    public function twitter_auth() {
        $curl_conn = curl_init();

        //Set up the URL to query Twitter
        $user_followers = "https://twitter.com/account/verify_credentials.json";
        $twitter_user_name = htmlentities($_POST['twitter_username'], ENT_QUOTES, 'utf-8');
        $twitter_user_password = htmlentities($_POST['twitter_password'], ENT_QUOTES, 'utf-8');
        //Set cURL options

        curl_setopt($curl_conn, CURLOPT_URL, $user_followers); //URL to connect to
        curl_setopt($curl_conn, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //Use basic authentication
        curl_setopt($curl_conn, CURLOPT_USERPWD, $twitter_user_name.':'.$twitter_user_password); //Set u/p
        curl_setopt($curl_conn, CURLOPT_SSL_VERIFYPEER, false); //Do not check SSL certificate (but use SSL of course), live dangerously!
        curl_setopt($curl_conn, CURLOPT_RETURNTRANSFER, 1); //Return the result as string

        // Result from querying URL. Will parse as xml
        $output_glee = curl_exec($curl_conn);

        // close cURL resource. It's like shutting down the water when you're brushing your teeth.
        curl_close($curl_conn);

        $array_glee = json_decode($output_glee, true);
        
        if (isset($array_glee['id'])) {

            if ($this->player->twitter_user_id == $array_glee['id']) {
                $twitter_auth = $this->edit_profile_page($this->skin->error_box($this->lang->twitter_already_verified));
                return $twitter_auth;
            }

            $this->player->twitter_user_id = $array_glee['id'];
            if (!intval($this->player->twitter_last)) {
                $this->player->twitter_last = 0;
            }
            $check_query = $this->db->execute("SELECT COUNT(`twitter_id`) AS `c` FROM `twitter` WHERE `twitter_user_id`=? AND `twitter_id` > ? ", array($this->player->twitter_user_id, $this->player->twitter_last));
            if ($check = $check_query->fetchrow()) {

                $count_query = $this->db->execute("SELECT COUNT(`twitter_id`) AS `c` FROM `twitter` AS `t` INNER JOIN `angst` AS `a` ON `t`.`angst_id`=`a`.`id` WHERE `a`.`type`=0 AND `t`.`twitter_user_id`=? AND `t`.`twitter_id` > ?", array($this->player->twitter_user_id, $this->player->twitter_last));

                if ($count = $count_query->fetchrow()) {
                    $this->player->angst_count = $this->player->angst_count + $count['c'];
                    $this->player->glee_count = $this->player->glee_count + ($check['c'] - $count['c']); // minus one because >= in the check query
                }
                $last_query = $this->db->execute("SELECT `twitter_id` FROM `twitter` WHERE `twitter_user_id`=? ORDER BY `twitter_id` DESC LIMIT 1");
                $last = $last_query->fetchrow();
                $this->player->twitter_last = $last['twitter_id'];
            }
            $this->player->update_player(true);
        } else {
            $twitter_auth = $this->edit_profile_page($this->skin->error_box($this->lang->twitter_not_verified));
            return $twitter_auth;
        }

        $twitter_auth = $this->edit_profile_page($this->skin->success_box($this->lang->twitter_succeeded));
        return $twitter_auth;
    }

   /**
    * Much quicker than reverifying
    */
    public function twitter_recount() {
        if (!intval($this->player->twitter_last)) {
            $this->player->twitter_last = 0;
        }
        $check_query = $this->db->execute("SELECT COUNT(`twitter_id`) AS `c` FROM `twitter` WHERE `twitter_user_id`=? AND `twitter_id` > ? ", array($this->player->twitter_user_id, $this->player->twitter_last));
        if ($check = $check_query->fetchrow()) {
            if ($check['c'] > 0) {
                
                $count_query = $this->db->execute("SELECT COUNT(`twitter_id`) AS `c` FROM `twitter` AS `t` INNER JOIN `angst` AS `a` ON `t`.`angst_id`=`a`.`id` WHERE `a`.`type`=0 AND `t`.`twitter_user_id`=? AND `t`.`twitter_id` > ?", array($this->player->twitter_user_id, $this->player->twitter_last));

                if ($count = $count_query->fetchrow()) {
                    $this->player->angst_count = $this->player->angst_count + $count['c'];
                    $this->player->glee_count = $this->player->glee_count + ($check['c'] - $count['c']); // minus one because >= in the check query
                }
                $last_query = $this->db->execute("SELECT `twitter_id` FROM `twitter` WHERE `twitter_user_id`=? ORDER BY `twitter_id` DESC LIMIT 1", array($this->player->twitter_user_id));
                $last = $last_query->fetchrow();
                $this->player->twitter_last = $last['twitter_id'];
                $this->player->update_player(true);
            } else {
                $twitter_auth = $this->edit_profile_page($this->skin->error_box($this->lang->twitter_no_new));
                return $twitter_auth;
            }
        }
        $twitter_auth = $this->edit_profile_page($this->skin->success_box($this->lang->twitter_recounted));
        return $twitter_auth;
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
            $update_profile = $this->edit_profile_page($this->skin->error_box($this->lang->email_wrong_format));
            return $update_profile;
        }

        if (!preg_match("#^https?://(?:[^<>*\"]+|[a-z0-9/\._\- !]+)$#iU", $_POST['avatar']) && $_POST['avatar']) {
            $update_profile = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_wrong_format));
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

        // Treat a gravatar like a submitted URL.
        if($_POST['avatar_type']=="gravatar")
            $_POST['avatar_url'] = $_POST['avatar_gravatar'];

        // Treat a library image similarly to a submitted URL, but check first 'cos it's local
        if($_POST['avatar_type']=="library") {
            $_POST['avatar_url'] = "images/library/" . $_POST['avatar_library'];
            $exists = file_exists($_POST['avatar_url']);
        }

        // Ultimately treat an upload like a submitted URL too. But upload it first.
        if($_POST['avatar_type']=="upload") {
            if(!empty($_FILES['avatar_upload']['error'])) {
                $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_upload_no));
                return $update_avatar;
            }

            // Put it where you want it!
            $file_name = $_FILES['avatar_upload']['name'];
            $file_type = strtolower(strrchr($file_name, "." ));
            $target_path = "images/avatars/" . $this->player->id . ".jpg";
            if(move_uploaded_file($_FILES['avatar_upload']['tmp_name'], $target_path)) {
                $_POST['avatar_url'] = $target_path;
                $exists = file_exists($target_path);
            } else {
                $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_upload_no));
                return $update_avatar;
            }
        }

        // I'll take whatever you've got...
        if(isset($_POST['avatar_type'])) {
            if(!$exists) $exists = @get_headers($_POST['avatar_url']);
            if($exists) {
                $this->player->avatar = $_POST['avatar_url'];
                $this->player->update_player();
                $_POST['avatar_url'] = "";
                $update_avatar = $this->edit_profile_page($this->skin->success_box($this->lang->avatar_updated));
            } else
                $update_avatar = $this->edit_profile_page($this->skin->error_box($this->lang->avatar_url_notfound));
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
        
        if ($this->settings['avatar_url'])
            $avatar_options .= $this->skin->edit_avatar_url();
        if ($this->settings['avatar_gravatar'])
            $avatar_options .= $this->skin->edit_avatar_gravatar( $this->player->email );
        if ($this->settings['avatar_upload'] && is_writeable('images/avatars'))
            $avatar_options .= $this->skin->edit_avatar_upload();
        if ($this->settings['avatar_library'] && is_writeable('images/library')) {
            $handle = opendir('images/library');
            while (false !== ($file = readdir($handle))) {
                if($file=="." || $file==".." || $file=="Thumbs.db") continue;
                $library .= "<option>${file}</option>";
            }
            $avatar_options .= $this->skin->edit_avatar_library( $library );
        }

        $edit_profile = $this->skin->edit_profile($this->player, $gender_list, $show_email, "section=public&amp;", $message);
        $edit_avatar = $this->skin->edit_avatar($avatar_options);
        $edit_password = $this->skin->edit_password($this->player->id);
        $edit_twitter = $this->skin->twitter_auth();
        return $edit_profile.$edit_avatar.$edit_password.$edit_twitter;
    }

}
?>
