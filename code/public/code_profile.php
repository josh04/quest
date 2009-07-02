<?php
/**
 * profile page generation code
 *
 * @author josh04
 * @package code_public
 */
class code_profile extends code_common {

    public $profile;

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_profile");

        if ($_GET['name']) {
            $success = $this->make_profile_player_name($_GET['name']);
        } else {
            $success = $this->make_profile_player_id($_GET['id']);
        }

        if ($success) {
            $code_profile = $this->make_profile();
        } else {
            $code_profile = $this->skin->warning_page($this->skin->lang_error->player_not_found);
        }


        parent::construct($code_profile);
    }

   /**
    * makes player object
    * 
    * @param integer $id player id
    * @return bool success
    */
    public function make_profile_player_id($id) {
        $this->profile = new code_player;
        $this->profile->db =& $this->db;
        if ($this->profile->get_player_by_id(intval($id))) {
            return true;
        } else {
            return false;
        }
    }

   /**
    * makes player object
    *
    * @param string $name player name
    * @return bool success
    */
    public function make_profile_player_name($name) {
        $this->profile = new code_player;
        $this->profile->db =& $this->db;
        if ($this->profile->get_player_by_name($name)) {
            return true;
        } else {
            return false;
        }
    }

   /**
    * assigns html to profile object
    *
    * @return string html
    */
    public function make_profile() {
      if ($this->profile->msn) {
          $this->profile->im_links .= $this->skin->im_link('MSN', $this->profile->msn);
      }
      if ($this->profile->aim) {
          $this->profile->im_links .= $this->skin->im_link('AIM', $this->profile->aim);
      }
      if ($this->profile->skype) {
          $this->profile->im_links .= $this->skin->im_link('Skype', $this->profile->skype);
      }

      $this->profile->age = intval(((time() - $this->profile->registered) / 3600) / 24);

      $this->profile->registered = date('F j, Y', $this->profile->registered);

      if ($this->profile->id == $this->player->id) {
          $this->profile->edit = $this->skin->edit_profile_link();
      } else {
          $this->profile->edit = "<a href='index.php?page=profile&amp;friends=add&amp;id=".$this->profile->id."'>Add to friends</a>"; // (TODO) friends, as always.
      }

      if ($this->profile->last_active > (time()-(60*15))) {
          $this->profile->is_online = "online";
      } else {
          $this->profile->is_online = "offline";
      }

      $make_profile = $this->skin->make_profile($this->profile);
      return $make_profile;
      
    }

}
?>
