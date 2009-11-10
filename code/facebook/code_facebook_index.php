<?php
/**
 * facebook setup
 *
 * @author josh04
 * @package code_facebook
 */
class code_facebook_index extends _code_facebook {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_facebook_index");

        $code_facebook = $this->facebook_switch();

        return $code_facebook;
    }

   /**
    * decides which action
    *
    * @return string html
    */
    public function facebook_switch() {
        if ($_GET['action'] == "angst") {
            $facebook_switch = $this->facebook_angst();
            return $facebook_switch;
        }

        if ($_GET['action'] == 'show_wall_offer') {
            $facebook_switch = $this->facebook_display($this->skin->angst_with_facebook_form());
            return $facebook_switch;
        }

        $facebook_switch = $this->facebook_display();
        return $facebook_switch;
    }

   /**
    * add the moods to the db
    *
    * @return string html
    */
    public function facebook_angst() {
        $angst = htmlentities($_POST['angst'], ENT_QUOTES, 'utf-8');

        if (strlen($angst) < 3) {
            $code_index = $this->facebook_display($this->skin->error_box($this->lang->angst_too_short));
            return $code_index;
        }

        $check_query = $this->db->execute("SELECT * FROM `angst` WHERE `phpsessid`=? ORDER BY `time` DESC LIMIT 1 ", array(session_id()));
/*
        if ($check = $check_query->fetchrow()) {
            if (time() - $check['time'] < 60) {
                $angst_reply = $this->facebook_display($this->skin->error_box($this->lang->angst_reply_timer));
                return $angst_reply;
            }
        }

        if ($this->player->is_member) {
            switch($_POST['submit']) {
                case 'Glee':
                    $this->player->glee_count++;
                    $this->player->update_player(true);
                    break;
                case 'Angst':
                default:
                    $this->player->angst_count++;
                    $this->player->update_player(true);
            }

        }
*/
        switch($_POST['submit']) {
            case 'Glee':
                $type = 1;
                break;
            case 'Angst':
            default:
                $type = 0;
        }

        $angst_insert['angst'] = nl2br($angst);
        $angst_insert['type'] = $type;
        $angst_insert['time'] = time();
        $angst_insert['phpsessid'] = $this->facebook->api_client->session_key;

        $this->db->AutoExecute('angst', $angst_insert, 'INSERT');
        $code_index = $this->skin->redirect_after_angst();
        return $code_index;
    }

   /**
    * shows the main page, facebook-style
    *
    * @param string $message error tyme
    * @return string html
    */
    public function facebook_display($message = "") {
        
        if (isset($_GET['start'])) {
            $limit = intval($_GET['start']).",10";
        } else {
            $limit = "0,10";
        }

        $angst_query = $this->db->execute("SELECT * FROM `angst` ORDER BY `time` DESC LIMIT ".$limit);
        
        if ($angst_query->NumRows() == 0) {
            unset($angst_query);
            $angst_query = $this->db->execute("SELECT * FROM `angst` ORDER BY `time` DESC LIMIT 0,10");
        }

        $count_query = $this->db->execute("SELECT COUNT(*) AS `c` FROM `angst`");
        $count = $count_query->fetchrow();

        $paginate = $this->paginate($count['c'], intval($_GET['start']), 10);


        $angst_html = "";
        $angst_ids = array();
        $angst_array = array();
        while ($angst = $angst_query->fetchrow()) {
                $angst_ids[] = $angst['id'];
                $angst_array[$angst['id']] = $angst;
        }

        $replies_count_query = $this->db->execute("SELECT `angst_id`, COUNT(*) AS 'c' FROM `angst_replies`
            GROUP BY `angst_id`", array());

        while ($count = $replies_count_query->fetchrow()) {
            $reply_count[$count['angst_id']] = $count['c'];
        }

        $angst_return = "";

        foreach ($angst_array as $id => $single_angst) {

            if ($this->player->is_member) {
                $reply_form = $this->skin->reply_box($this->skin->reply_form($id), $replies[$id]);
            } else {
                $reply_form = $this->skin->reply_box($this->skin->reply_form_guest($id), $replies[$id]);
            }

            if (!$reply_count[$id]) {
                $reply_count[$id] = 0;
            }

            if ($single_angst['type']) {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'Glee');
            } else {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'Angst');
            }
        }

        $angst_html .= $paginate;
        $result = array();
        if ($this->player->id) {
            //$result = $this->facebook->api_client->fql_query("SELECT uid, name FROM user WHERE uid=".$this->player->id);
        }
        $facebook_display = $this->skin->facebook_display($angst_html, $result[0]['name'], $result[0]['uid'], $message);
        return $facebook_display;
    }
}
?>
