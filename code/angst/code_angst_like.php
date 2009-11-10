<?php
/**
 * handles the +1/-1 is this a quality angst?-o-meter
 *
 * @author josh04
 * @package code_angst
 */
class code_angst_like extends _code_angst {

    public $player_class = "code_player_profile";

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate();

        $id = intval($_POST['angst_id']);

        if (!$id) {
            return $this->skin->error_box($this->lang->no_angst_id);
        }

        $code_angst_like = $this->vote($id);

        if ($_GET['action'] == 'ajax_vote') {
            $this->ajax_mode = true;
            return $code_angst_like;
        }

        return $code_angst_like;
    }

   /**
    * casts a vote
    *
    * @param int $id angst_id
    * @param bool $like true for like, false for dislike
    * @return string html
    */
    public function vote($id, $like) {

        if ($this->player->voted_on[$id]) {
            return $this->skin->error_box($this->lang->already_voted);
        }

        $check_query = $this->db->execute("SELECT `id` FROM `angst` WHERE `id`=?", array($id));

        if (!$check = $check_query->fetchrow()) {
            return $this->skin->error_box($this->lang->no_angst_id);
        }

        if ($_POST['type'] == "dislike") {
            $this->player->voted_on[$id] = -1;
            $voted_query = $this->db->execute("UPDATE `angst` SET `dislike`=`dislike`+1 WHERE `id`=?", array($id));
            $success = $this->skin->success_box($this->lang->disliked);
        } else {
            $this->player->voted_on[$id] = 1;
            $voted_query = $this->db->execute("UPDATE `angst` SET `like`=`like`+1 WHERE `id`=?", array($id));
            $success = $this->skin->success_box($this->lang->liked);
        }

        if (!$voted_query) {
            return $this->skin->error_box($this->lang->error_voting);
        }

        $this->player->update_player(true);

        return $success;
    }

}
?>
