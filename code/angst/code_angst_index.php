<?php
/**
 * code_index.class.php
 *
 * generate the page index
 * @package code_angst
 * @author josh04
 */
class code_angst_index extends code_common {
    
    public $player_class = "code_player_profile";
    public $override_skin = "angst"; // This is a little bit of a hack; technically the extra skin_common should be in _skin_angst.php

   /**
    * builds the logged-in index
    *
    * @param string $message error message
    * @return string html
    */
    public function index_player($message = "") {
        if ($_GET['action'] == "logged_out") {
            $message .= $this->skin->error_box($this->lang->logged_out);
        }
        
        $online_list = "";
        $mail = "";
        $login_box = "";

        $angst_html = $this->angst_board();

        if ($this->player->is_member) {
            $online_list = $this->online_list();
            $mail = $this->mail();
            $profile_box = $this->skin->profile_box($this->player->id, $this->player->registered_date);
        } else {
            $username = htmlentities($_POST['username'], ENT_QUOTES, 'utf-8');
            $login_box = $this->skin->login_box($username);
        }

        $angst_text = htmlentities($_POST['angst'], ENT_QUOTES, 'utf-8');
        
        $index_player = $this->skin->index_player($this->player, $angst_html, $online_list, $mail, $login_box, $profile_box, $angst_text, $message);
        return $index_player;
    }

   /**
    * Makes the "angst board!"
    *
    * @return <type>
    */
    public function angst_board() {
        
        if (isset($_GET['start'])) {
            $limit = intval($_GET['start']).",10";
        } else {
            $limit = "0,10";
        }

        $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `approved`=1 ORDER BY `time` DESC LIMIT ".$limit);

        if ($angst_query->NumRows() == 0) {
            unset($angst_query);
            $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `approved`=1 ORDER BY `time` DESC LIMIT 0,10");
        }

        $count_query = $this->db->execute("SELECT COUNT(*) AS `c` FROM `angst` WHERE `approved`=1");
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
                $reply_form = $this->skin->reply_box($this->skin->reply_form($id));
            } else {
                $reply_form = $this->skin->reply_box($this->skin->reply_form_guest($id));
            }
            if (!$reply_count[$id]) {
                $reply_count[$id] = 0;
            }

            $single_angst['date'] = date("jS M, h:i A", $single_angst['time']);
            
            if ($single_angst['type']) {
                
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'glee', $reply_count[$id]);
            } else {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'angst', $reply_count[$id]);
            }
        }

        return $angst_html.$paginate;

    }



   /**
    * builds the online list
    *
    * @return string html
    */
    public function online_list() {
        $online_query = $this->db->execute("SELECT id, username FROM players WHERE (last_active > (?))", array((time()-(60*15))));

        $online_list = "";
        while($online = $online_query->fetchrow()) {
            $online_list[] = $this->skin->member_online_link($online['id'], $online['username']);
        }
        $list = implode(", ",$online_list);
        $online_list = $this->skin->online_list($list);

        return  $online_list;
    }

   /**
    * recent mail messages
    *
    * @return string html
    */
    public function mail() {
        $mail_query = $this->db->execute("SELECT `mail`.`id`, `mail`.`from`, `mail`.`status`, `mail`.`subject`, `players`.`username`
                          FROM `mail`
                          INNER JOIN `players` ON `players`.`id` = `mail`.`from`
                          WHERE `to`=? ORDER BY `time` DESC LIMIT 5",array($this->player->id));

        if (!$mail_query) {
            $this->skin->log_entry($this->lang->error_getting_mail, 0);
        }

        if ($mail_query->numrows()==0) {
            $mail = $this->skin->log_entry($this->lang->no_mail, 0);
        }

        while($mail_row = $mail_query->fetchrow()) {
            $mail_row['subject'] = str_replace(array("<",">"),array("&lt;","&gt;"),$mail_row['subject']);
            $mail .= $this->skin->mail_entry($mail_row);
        }
        
        $mail_box = $this->skin->mail_box($mail);
        return $mail_box;
    }

   /**
    * add the moods to the db
    *
    * @return string html
    */
    public function angst() {
        $angst = htmlentities($_POST['angst'], ENT_QUOTES, 'utf-8');

        if (strlen($angst) < 3) {
            $code_index = $this->index_player($this->skin->error_box($this->lang->angst_too_short));
            return $code_index;
        }

        $check_query = $this->db->execute("SELECT * FROM `angst` WHERE `phpsessid`=? ORDER BY `time` DESC LIMIT 1 ", array(session_id()));

        if ($check = $check_query->fetchrow()) {
            if (time() - $check['time'] < 60) {
                $angst_reply = $this->index_player($this->skin->error_box($this->lang->angst_reply_timer));
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
        $angst_insert['phpsessid'] = session_id();

        $this->db->AutoExecute('angst', $angst_insert, 'INSERT');

        header("Location: index.php?page=index");
    }

   /**
    * chooses what to do
    *
    * @return string html
    */
    public function index_switch() {

        if ($_GET['action'] == "angst") {
            $code_index = $this->angst();
            return $code_index;
        }

        $code_index = $this->index_player();
        return $code_index;
    }

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct($code_other = "") {

        if ($code_other) {
            parent::construct($code_other);
            return;
        }

        $this->initiate("skin_angst_index");

        $code_index = $this->index_switch();

        
        parent::construct($code_index);
    }
    
}

?>
