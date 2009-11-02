<?php
/**
 * a single bemoanst of the hear't
 *
 * @author josh04
 * @package code_public
 */
class code_angst_single extends code_common {
    
    public $player_class = "code_player_profile";
    public $override_skin = "angst"; // This is a little bit of a hack; technically the extra skin_common should be in _skin_angst.php

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        if ($code_other) {
            parent::construct($code_other);
            return;
        }

        $this->initiate("skin_angst_single");

        if ($_GET['action'] == "ajax_replies") {
            $code_index = $this->ajax_replies();
            print $code_index;
            return;
        }

        if ($_GET['action'] == "ajax_reply") {
            $code_index = $this->angst_reply();
            print $code_index;
            return;
        }

        $code_angst_single = $this->angst_switch();


        parent::construct($code_angst_single);
    }

    public function angst_switch() {
        if (!intval($_GET['id'])) {
            $angst_switch = $this->skin->error_box($this->lang->no_angst_id);
            return $angst_switch;
        }

        if ($_GET['action'] == "angst-reply") {
            $angst_switch = $this->angst_reply();
            if (!$angst_switch) {
                header("Location: index.php?page=single&id=".intval($_GET['id']));
            } else {
                return $this->angst_single($_POST['id'], $angst_switch);
            }
        }

        $angst_switch = $this->angst_single(intval($_GET['id']));
        return $angst_switch;

    }

   /**
    * a single angst
    *
    * @param int $id angst id
    * @param string $message error tyme
    * @return string html
    */
    public function angst_single($id, $message="") {

        if ($_GET['action'] == "bookmarked") {
            $message .= $this->skin->success_box($this->skin->bookmarked(intval($_GET['id'])));
        }

        if ($_GET['action'] == "not_bookmarked") {
            $message .= $this->skin->error_box($this->lang->bookmark_failed);
        }

        $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `id`=? LIMIT 0,1", array($id));

        if (!$angst_query) {
            $angst_single = $this->skin->error_box($this->lang->no_angst_id);
            return $angst_single;
        }

        if ($angst_db['type']) {
            $type = 'glee';
        } else {
            $type= 'angst';
        }

        $angst_db = $angst_query->fetchrow();

        if (!$angst_db['approved']) {
            $message .= $this->skin->error_box($this->lang->angst_not_yet_approved);
        }

        $angst_db['date'] = date("jS M, h:i A", $angst_db['time']);

        $replies = $this->replies($angst_db['id'], intval($_GET['start']));

        if ($replies) {
            $replies = $this->skin->replies($replies, $type, 'block');
        } else {
            $replies = $this->skin->replies($replies, $type, 'none');
        }

        if ($this->player->is_member) {
            $reply_form = $this->skin->reply_form($angst_db['id']);
        } else {
            $reply_form = $this->skin->reply_form_guest($angst_db['id']);
        }

        $reply_count_query = $this->db->execute("SELECT `angst_id`, COUNT(*) AS 'c' FROM `angst_replies`
            WHERE `angst_id`=?", array($id));
        
        $reply_count = $reply_count_query->fetchrow();
        
        $script = $this->skin->user_id_script($this->player->id, $this->player->username);

        $bookmark_link = "";
        if (!isset($this->player->bookmarks[$angst_db['id']])) {
            $bookmark_link = $this->skin->bookmark_link($angst_db['id']);
        } else {
            $bookmark_link = $this->skin->unbookmark_link($angst_db['id']);
        }

        $angst_single = $this->skin->angst_single($angst_db, $replies, $reply_form, $type, $reply_count['c'], $script, $bookmark_link, $message);

        return $angst_single;
    }

   /**
    * Fetches reply html
    *
    * @param int $id angst id
    * @return string html
    */
    public function replies($id, $start = 0) {
        $id = intval($id);
        $start = intval($start);
        $replies_query = $this->db->execute("SELECT `r`.*, `p`.`username` FROM `angst_replies` AS `r`
            LEFT JOIN `players` AS `p` ON `r`.`player_id`=`p`.`id`
            WHERE `r`.`angst_id`=?
            ORDER BY `time` ASC
            LIMIT ".$start.",6
            ", array($id)); // We get six even though we're only sending five, in order to check if there are more to come

        $replies = '';
        $more_to_come = 0;
        if ($replies_query->RecordCount) {
            $reply_counter = 0;
            while ($reply = $replies_query->fetchrow()) {
                if ($reply_counter == 5) {
                    $more_to_come = 1;
                    continue;
                }
                $replies .= $this->skin->reply($reply);
                $reply_counter++;
            }
            $bookmarks_array = json_decode($_COOKIE['bookmarks'], true);

            if ($bookmarks_array[$id] < $start + $reply_counter) {
                $bookmarks_array[$id] = $start + $reply_counter;
            }


            setcookie('bookmarks', json_encode($bookmarks_array), time()+60*60*24*300);
            $replies .= $this->skin->start_input(intval($_GET['start'] + $reply_counter), $more_to_come);
        }
        return $replies;
    }

   /**
    * Ajax replies
    *
    * @return string html
    */
    public function ajax_replies() {
        $ajax_replies = $this->replies($_GET['id'], $_GET['start']);
        return $ajax_replies;
    }

   /**
    * Angst reply for ajax
    *
    * @return string html
    */
    public function angst_reply() {
        if (!$_POST['angst-id']) {
            $angst_reply = $this->skin->error_box($this->lang->no_angst_id);
            return $angst_reply;
        }

        if (!$this->player->is_member) {
            $angst_reply = $this->skin->error_box($this->lang->cannot_reply);
            return $angst_reply;
        }

        $reply = nl2br(htmlentities($_POST['angst-reply'], ENT_QUOTES, 'utf-8'));

        if (strlen($reply) < 3) {
            $code_index = $this->skin->error_box($this->lang->angst_too_short);
            return $code_index;
        }

       /* $check_query = $this->db->execute("SELECT * FROM `angst_replies` WHERE `player_id`=? ORDER BY `time` DESC LIMIT 1 ", array($this->player->id));

        if ($check = $check_query->fetchrow()) {
            if (time() - $check['time'] < 60) {
                $angst_reply = $this->skin->error_box($this->lang->angst_reply_timer);
                return $angst_reply;
            }
        }*/

        $angst_insert['player_id'] = $this->player->id;
        $angst_insert['angst_id'] = intval($_POST['angst-id']);
        $angst_insert['reply'] = $reply;
        $angst_insert['time'] = time();

        $this->db->AutoExecute('angst_replies', $angst_insert, 'INSERT');
        
        $bookmarks_array = json_decode($_COOKIE['bookmarks'], true);

        $bookmarks_array[intval($_POST['angst-id'])]++;

        setcookie('bookmarks', json_encode($bookmarks_array), time()+60*60*24*300);
        
        return false;
    }
}
?>
