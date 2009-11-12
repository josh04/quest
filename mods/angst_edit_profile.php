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
    * diaplays the edit page
    *
    * @return string html
    */
    public function edit_profile_page($message = "") {
        $parent = parent::edit_profile_page($message);
        $edit_twitter = $this->skin->twitter_auth();
        return $parent.$edit_twitter;
    }

}
?>
