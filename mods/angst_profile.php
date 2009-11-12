<?php
/**
 * profile page generation code
 *
 * @author josh04
 * @package code_public
 */
class angst_profile extends code_profile {

    public $profile;
    public $player_class = 'code_player_profile';


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
            if(!in_array($this->profile->id, array_keys($this->player->friends))) {
                $interaction .= $this->skin->add_friend_link($this->profile->id);
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
