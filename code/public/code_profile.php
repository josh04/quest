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
    public function construct_page() {
        $this->initiate("skin_profile");

        if ($_GET['name']) {
            $this->make_profile_player_name($_GET['name']);
        } else {
            $this->make_profile_player_id($_GET['id']);
        }

        $this->make_profile_html();

        $code_profile = $this->skin->make_profile($this->profile);

        parent::construct_page($code_profile);
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
        if ($this->profile->get_user_by_id(intval($id))) {
            return true;
        } else {
            $this->error_page("Player not found.");
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
        if ($this->profile->get_user_by_name($name)) {
            return true;
        } else {
            $this->error_page("Player not found.");
        }
    }

   /**
    * assigns html to profile object
    *
    */
    public function make_profile_html() {
      if ($this->profile->msn) {
          $this->profile->im_links .= $this->skin->im_link('msn', $this->profile->msn);
      }
      if ($this->profile->aim) {
          $this->profile->im_links .= $this->skin->im_link('aim', $this->profile->aim);
      }
      if ($this->profile->skype) {
          $this->profile->im_links .= $this->skin->im_link('skype', $this->profile->skype);
      }

      $this->profile->age = intval(((time() - $this->profile->registered) / 3600) / 24);

      $this->profile->registered = date('F j, Y', $this->profile->registered);

      if ($this->profile->id == $this->player->id) {
          $this->profile->edit = "<a href='index.php?page=profile_edit'>Edit Profile</a>";
      } else {
          $this->profile->edit = "<a href='index.php?page=profile&amp;friends=add&amp;id=".$this->profile->id."'>Add to friends</a>";
      }

      if ($this->profile->last_active > (time()-(60*15))) {
          $this->profile->is_online = "online";
      } else {
          $this->profile->is_online = "offline";
      }
      
    }

}
?>
