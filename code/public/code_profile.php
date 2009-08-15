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

        $this->profile = new code_player;

        if ($_GET['name']) {
            $success = $this->profile->get_player($_GET['name']);
        } else {
            $success = $this->profile->get_player($_GET['id']);
        }

        if ($success) {
            $code_profile = $this->make_profile($message);
        } else {
            $code_profile = $this->skin->error_page($this->skin->lang_error->player_not_found);
        }


        parent::construct($code_profile);
    }

   /**
    * assigns html to profile object
    *
    * @param $message any error messages
    * @return string html
    */
    public function make_profile($message = '') {

        $this->profile->getFriends();
        if ($_GET['friends']=="add") {
            $message = $this->add_as_friend();
        }

        if ($_GET['action'] == "donate") {
            $message = $this->donate();
        }

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
            $this->player->getFriends();
            if(!in_array($this->profile->id, array_keys($this->player->friends))) {
                $this->profile->edit = $this->skin->add_friend_link($this->profile->id); // (TODO) friends, as always.
            }
        }

        if ($this->profile->last_active > (time()-(60*15))) {
            $this->profile->is_online = "online";
        } else {
            $this->profile->is_online = "offline";
        }

        $this->profile->ratio = ($this->profile->deaths>0?number_format($this->profile->kills/$this->profile->deaths,2):"0");

        $this->profile->friendcount = count($this->profile->friends);
        if($this->profile->friendcount==0) {
            $friendlist = $this->profile->username." has no friends yet!";
        } else {
            $friendlist = $this->skin->friendlist($this->profile->friends);
        }

        $make_profile = $this->skin->make_profile($this->profile, $friendlist, $message);
        return $make_profile;
      
    }

   /**
    * sends a friend request or adds a friend link
    *
    * @return bool success
    */
    public function add_as_friend() {
        if($this->player->id == $this->profile->id) {
            $message = $this->skin->error_box($this->skin->lang_error->cant_self_friend);
            return $message;
        }

        $this->player->getFriends();

        if(in_array($this->profile->id, array_keys($this->player->friends))) {
            $message = $this->skin->error_box($this->skin->lang_error->already_friends.$this->profile->username.".");
            return $message;
        }

        // Get friendship status
        $inputarr = array($this->profile->id, $this->player->id, $this->player->id, $this->profile->id);
        $fq = $this->db->execute("SELECT * FROM `friends` WHERE (`id1`=? AND `id2`=?) OR (`id1`=? AND `id2`=?)",$inputarr);
        if($fq->numrows()==1) {
            $f = $fq->fetchrow();
            if($f['id1']==$this->player->id) {
                $message = $this->skin->error_box($this->skin->lang_error->awaiting_friend_response);
                return $message;
            } else {
                // Accepting request!
                $inputArr = array($this->profile->id, $this->player->id);
                $this->db->execute("UPDATE `friends` SET `accepted`=1 WHERE `id1`=? AND `id2`=?",$inputArr);
                $message = $this->skin->success_box($this->skin->lang_error->now_friends.$this->profile->username.".");
                return $message;
            }
        }
        // All else has failed, let's send a request
        $inputArr = array($this->player->id, $this->profile->id, false);
        $this->db->execute("INSERT INTO `friends` (`id1`,`id2`,`accepted`) VALUES (?,?,?)",$inputArr);
        $message = $this->skin->success_box($this->skin->lang_error->request_sent.$this->profile->username.".");
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
            return $this->skin->error_box($this->skin->lang_error->not_enough_cash_to_donate);
        }

        $this->profile->gold = $this->profile->gold + $amount;
        $this->player->gold = $this->player->gold - $amount;

        $this->profile->update_player();
        $this->player->update_player();

        return $this->skin->donated($amount, $this->profile->username);
    }

}
?>
