<?php
/**
 * profile page generation code
 *
 * @author josh04
 * @package code_public
 */
class code_profile extends code_common {

    public $profile;
    public $player_flags = array("profile", "rpg", "friends");
    
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_profile");
        
        $this->profile = $this->core("player");

       // No ID or name? Show the user's profile.
        if (!isset($_GET['name']) && !isset($_GET['id'])) {
            $success = $this->profile->get_player((int) $this->player->id);
        } else if ($_GET['name']) {
            $success = $this->profile->get_player($_GET['name']);
        } else {
            
            $success = $this->profile->get_player((int) $_GET['id']); //important
        }

        if ($success) {
            $code_profile = $this->make_profile($message);
        } else {
            $code_profile = $this->skin->error_page($this->lang->player_not_found);
        }


        return $code_profile;
    }

   /**
    * assigns html to profile object
    * (TODO) Donate needs to splintered off into a seperate page
    *        Add a hook-in system for mods to add interact links
    *
    * @param $message any error messages
    * @return string html
    */
    public function make_profile($message = '') {

        if ($_GET['friends']=="add") {
            $message = $this->add_as_friend();
        }

        if ($_GET['action'] == "donate") {
            $message = $this->donate();
        }

        $custom_fields = json_decode($this->settings->get['custom_fields'], true);

        foreach ($custom_fields as $field => $default) {
            if (in_array($field, array("msn", "aim", "skype", "description", "gender", "avatar"))) {
                continue;
            }

            $this->profile->extras .= $this->skin->extra_link($field, $this->profile->$field);
        }

        if (trim($this->profile->msn)) {
            $this->profile->im_links .= $this->skin->extra_link('MSN', $this->profile->msn);
        }
        if (trim($this->profile->aim)) {
            $this->profile->im_links .= $this->skin->extra_link('AIM', $this->profile->aim);
        }
        if (trim($this->profile->skype)) {
            $this->profile->im_links .= $this->skin->extra_link('Skype', $this->profile->skype);
        }

        $this->profile->age = intval(((time() - $this->profile->registered) / 3600) / 24);

        $this->profile->registered = date('F j, Y', $this->profile->registered);

        $interaction .= $this->skin->add_mail_link($this->profile->username);

        if ($this->profile->id == $this->player->id) {
            $interaction .= $this->skin->add_edit_link();
        } else {
            $interaction .= $this->skin->add_battle_link($this->profile->id);
            if(!in_array($this->profile->id, array_keys($this->player->friends))) {
                $interaction .= $this->skin->add_friend_link($this->profile->id);
            }
        }

        // Get interaction from the profile/interact hook
        $custom_interact = $this->hooks->get("profile/interact", HK_ARRAY);
        if($custom_interact) {
            foreach($custom_interact as $interact) {
                $interaction .= $this->skin->add_interact_link($interact['url'], $interact['caption']);
            }
        }

        if ($this->profile->last_active > (time()-(60*15))) {
            $this->profile->is_online = "online";
        } else {
            $this->profile->is_online = "offline";
        }

        if ($this->profile->deaths > 0) {
            $this->profile->ratio = number_format($this->profile->kills/$this->profile->deaths,2);
        } else {
            $this->profile->ratio = '0';
        }

        $this->profile->friendcount = count($this->profile->friends);
        if ($this->profile->friendcount==0) {
            $friendlist = $this->skin->no_friends($this->profile->username);
        } else {
            foreach($this->profile->friends as $friend) {
                $friendlist .= $this->skin->friend_entry($friend);
            }
        }

        $custom_fields = json_decode($this->settings->get['custom_fields'], true);

        if (empty($this->profile->description)) {
            $this->profile->description = $custom_fields['description'];
        }

        $make_profile = $this->skin->make_profile($this->profile, $friendlist, $interaction, $message);
        return $make_profile;
      
    }

   /**
    * sends a friend request or adds a friend link
    *
    * @return bool success
    */
    public function add_as_friend() {
        if ($this->player->id == $this->profile->id) {
            $message = $this->skin->error_box($this->lang->cant_self_friend);
            return $message;
        }

        if (in_array($this->profile->id, array_keys($this->player->friends))) {
            $message = $this->skin->error_box($this->lang->already_friends.$this->profile->username.".");
            return $message;
        }

        // Get friendship status
        $player_id_array = array($this->profile->id, $this->player->id, $this->player->id, $this->profile->id);
        $friend_query = $this->db->execute("SELECT * FROM `friends` WHERE (`id1`=? AND `id2`=?) OR (`id1`=? AND `id2`=?)",$player_id_array);
        if($friend_query->numrows()==1) {
            $friend_entry = $friend_query->fetchrow();
            if ($friend_entry['id1']==$this->player->id) {
                $message = $this->skin->error_box($this->lang->awaiting_friend_response);
                return $message;
            } else {
                // Accepting request!
                $player_id_array = array($this->profile->id, $this->player->id);
                $this->db->execute("UPDATE `friends` SET `accepted`=1 WHERE `id1`=? AND `id2`=?",$player_id_array);
                $message = $this->skin->success_box($this->lang->now_friends.$this->profile->username.".");
                return $message;
            }
        }
        // All else has failed, let's send a request
        $player_id_array = array($this->player->id, $this->profile->id, 0);
        $this->db->execute("INSERT INTO `friends` (`id1`,`id2`,`accepted`) VALUES (?,?,?)",$player_id_array);
        $this->core('mail_api');
        $this->mail_api->send($this->profile->id, $this->player->id, $this->skin->friend_request($this->player->id, $this->player->username), $this->lang->friend_request);
        $message = $this->skin->success_box($this->lang->request_sent.$this->profile->username.".");
        return $message;
    }

   /**
    * donate cash to someone
    *
    * @return string html
    */
    public function donate() {
        $amount = intval($_POST['donate']);

        if ($amount > $this->player->gold) {
            return $this->skin->error_box($this->lang->not_enough_cash_to_donate);
        }

        $this->profile->gold = $this->profile->gold + $amount;
        $this->player->gold = $this->player->gold - $amount;

        $this->profile->update_player();
        $this->player->update_player();

        return $this->skin->donated($amount, $this->profile->username);
    }

   /**
    * static menu function
    *
    * @param code_menu $menu the current menu object, allows adding of top/bottom and db etc
    * @param string $label The text label
    * @return string html
    */
    public static function code_profile_menu(&$menu, $label) {
        return $menu->player->username;
    }

}
?>
