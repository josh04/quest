<?php
/**
 * profile page generation code
 *
 * @author josh04
 * @package code_public
 */
class angst_profile extends code_profile {

    public $profile;


   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_profile");

        require_once("code/player/code_player_profile.php");
        $this->profile = new code_player_profile($this->settings);

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


        parent::construct($code_profile);
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

        $this->profile->getFriends();
        if ($_GET['friends']=="add") {
            $message = $this->add_as_friend();
        }

        $custom_fields = json_decode($this->settings['custom_fields'], true);

        foreach ($custom_fields as $field => $default) {
            if (in_array($field, array("msn", "aim", "skype", "description", "gender", "avatar"))) {
                continue;
            }

            $this->profile->extras .= $this->skin->extra_link($field, $this->profile->$field);
        }

        if ($this->profile->msn) {
            $this->profile->im_links .= $this->skin->extra_link('MSN', $this->profile->msn);
        }
        if ($this->profile->aim) {
            $this->profile->im_links .= $this->skin->extra_link('AIM', $this->profile->aim);
        }
        if ($this->profile->skype) {
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
                $interaction .= $this->skin->add_friend_link("index.php?page=profile&amp;id=".$this->profile->id."&amp;friends=add", "Add as friend");
            }
        }

        if ($this->profile->last_active > (time()-(60*15))) {
            $this->profile->is_online = "online";
        } else {
            $this->profile->is_online = "offline";
        }

        $this->profile->friendcount = count($this->profile->friends);
        if($this->profile->friendcount==0) {
            $friendlist = $this->profile->username." has no friends yet!";
        } else {
            foreach($this->profile->friends as $friend) {
                $friendlist .= $this->skin->friend_entry($friend);
            }
        }

        $custom_fields = json_decode($this->settings['custom_fields'], true);
        if(empty($this->profile->description)) $this->profile->description = $custom_fields['description'];

        $make_profile = $this->skin->make_profile($this->profile, $friendlist, $interaction, $message);
        return $make_profile;
      
    }

   /**
    * sends a friend request or adds a friend link
    *
    * @return bool success
    */
    public function add_as_friend() {
        if($this->player->id == $this->profile->id) {
            $message = $this->skin->error_box($this->lang->cant_self_friend);
            return $message;
        }

        $this->player->getFriends();

        if(in_array($this->profile->id, array_keys($this->player->friends))) {
            $message = $this->skin->error_box($this->lang->already_friends.$this->profile->username.".");
            return $message;
        }

        // Get friendship status
        $inputarr = array($this->profile->id, $this->player->id, $this->player->id, $this->profile->id);
        $fq = $this->db->execute("SELECT * FROM `friends` WHERE (`id1`=? AND `id2`=?) OR (`id1`=? AND `id2`=?)",$inputarr);
        if($fq->numrows()==1) {
            $f = $fq->fetchrow();
            if($f['id1']==$this->player->id) {
                $message = $this->skin->error_box($this->lang->awaiting_friend_response);
                return $message;
            } else {
                // Accepting request!
                $inputArr = array($this->profile->id, $this->player->id);
                $this->db->execute("UPDATE `friends` SET `accepted`=1 WHERE `id1`=? AND `id2`=?",$inputArr);
                $message = $this->skin->success_box($this->lang->now_friends.$this->profile->username.".");
                return $message;
            }
        }
        // All else has failed, let's send a request
        $inputArr = array($this->player->id, $this->profile->id, false);
        $this->db->execute("INSERT INTO `friends` (`id1`,`id2`,`accepted`) VALUES (?,?,?)",$inputArr);
        $message = $this->skin->success_box($this->lang->request_sent.$this->profile->username.".");
        return $message;
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
