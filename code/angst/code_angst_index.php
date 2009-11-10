<?php
/**
 * code_index.class.php
 *
 * generate the page index
 * @package code_angst
 * @author josh04
 */
class code_angst_index extends _code_angst {
    
    public $player_class = "code_player_profile";
    public $override_skin = "angst"; // This is a little bit of a hack; technically the extra skin_common should be in _skin_angst.php

   /**
    * builds the logged-in index
    *
    * @param string $message error message
    * @return string html
    */
    public function index_player($message = "") {
        switch ($_GET['action']) {
            case "logged_out":
                $message .= $this->skin->error_box($this->lang->logged_out);
                break;
            case "bookmarked":
                $message .= $this->skin->success_box($this->skin->bookmarked(intval($_GET['id'])));
                break;
            case "not_bookmarked":
                $message .= $this->skin->error_box($this->lang->bookmark_failed);
                break;
            case "unbookmarked":
                $message .= $this->skin->success_box($this->skin->unbookmarked(intval($_GET['id'])));
                break;
            case "not_unbookmarked":
                $message .= $this->skin->error_box($this->lang->remove_bookmark_failed);
                break;
        }
        
        $online_box = "";
        $mail_box = "";
        $login_box = "";
        $bookmarks_box = "";
        $username = "";

        $angst_html = $this->angst_board();

        if ($this->player->is_member) {
            $online_box = $this->online_list();
            $mail_box = $this->mail();
            $profile_box = $this->skin->profile_box($this->player->id, $this->player->registered_date);
            $bookmarks_box = $this->bookmarks_box();
            $username = $this->player->username;
        } else {
            $username = htmlentities($_POST['username'], ENT_QUOTES, 'utf-8');
            $login_box = $this->skin->login_box($username);
            $username = $this->lang->guest;
        }

        $boxes = $bookmarks_box.$online_box.$login_box.$profile_box.$mail_box;

        $angst_text = htmlentities($_POST['angst'], ENT_QUOTES, 'utf-8');

        $index_player = $this->skin->angst_index($angst_html, $boxes, $this->player->id, $username, $angst_text, $message);
        return $index_player;
    }

   /**
    * makes a bookmarks object and gets the box with the little numbers in it.
    *
    * @return string html
    */
    public function bookmarks_box() {
        require_once('code/angst/code_angst_bookmark.php');
        $bookmarks = new code_angst_bookmark($this->section, $this->page);
        $bookmarks->player =& $this->player;
        $bookmarks->skin =& $this->skin;

        $bookmarks_box = $bookmarks->code_index_bookmarks_box();
        return $bookmarks_box;
    }

   /**
    * Makes the "angst board!"
    *
    * @return string html
    */
    public function angst_board() {
        
        if (isset($_GET['start'])) {
            $limit = intval($_GET['start']).",10";
        } else {
            $limit = "0,10";
        }

        $approved = 1;
        
        if ($_GET['show'] == "unapproved") {
            $approved = 0;
        }

        $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `approved`=? ORDER BY `time` DESC LIMIT ".$limit, array($approved));

        if ($angst_query->NumRows() == 0) {
            unset($angst_query);
            $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `approved`=? ORDER BY `time` DESC LIMIT 0,10", array($approved));
        }

        $count_query = $this->db->execute("SELECT COUNT(*) AS `c` FROM `angst` WHERE `approved`=?", array($approved));
        $count = $count_query->fetchrow();

        $angst_ids = "";
        $angst_array = array();

        while ($angst = $angst_query->fetchrow()) {
            $angst_ids .= intval($angst['id']).", ";
            $angst_array[$angst['id']] = $angst;
        }

        $angst_ids = substr($angst_ids, 0, -2);
        $replies_count_query = $this->db->execute("SELECT `angst_id`, COUNT(*) AS 'c' FROM `angst_replies`
            WHERE `angst_id` IN (".$angst_ids.") GROUP BY `angst_id`");
        
        while ($number_count = $replies_count_query->fetchrow()) {
            $reply_count[$number_count['angst_id']] = $number_count['c'];
        }

        $formatted_angst = $this->format_angst($angst_array, $reply_count, $approved);
        $paginate = $this->paginate($count['c'], intval($_GET['start']), 10);
        
        $angst_board = $this->skin->angst_board($formatted_angst, $paginate);
        return $angst_board;

    }

   /**
    * makes the angst list look nice
    *
    * @param array $angst_array angst from db
    * @param array $reply_count array of reply numbers
    * @param int $approved is it approved?
    * @return string html
    */
    public function format_angst($angst_array, $reply_count, $approved = 1) {
        foreach ($angst_array as $id => $single_angst) {
            if ($this->player->is_member) {
                $reply_form = $this->skin->reply_box($this->skin->reply_form($id));
            } else {
                $reply_form = $this->skin->reply_box($this->skin->reply_form_guest($id));
            }
            if (!$reply_count[$id]) {
                $reply_count[$id] = 0;
            }

            $single_angst['date'] = $this->format_time($single_angst['time']);
            
            $bookmark_link = "";
            if (!isset($this->player->bookmarks[$single_angst['id']])) {
                $bookmark_link = $this->skin->bookmark_link($single_angst['id'], $reply_count[$id]);
            }

            $replies_link = "";
            $unapproved = "";
            if (!$approved) {
                $unapproved = $this->lang->unapproved;
            } else {
                $replies_link = $this->skin->replies_link($single_angst['id'], $reply_count[$id]);
            }

            if ($single_angst['type']) {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'glee',  $bookmark_link, $replies_link, $unapproved);
            } else {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'angst', $bookmark_link, $replies_link, $unapproved);
            }
        }

        return $angst_html;
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

        $new_angst_id = $this->db->Insert_Id();

        if ($this->player->is_member) {
            switch($_POST['submit']) {
                case 'Glee':
                    $this->player->glee_count++;
                    break;
                case 'Angst':
                default:
                    $this->player->angst_count++;
            }

            $bookmarks_array = json_decode($_COOKIE['bookmarks'], true);

            $bookmarks_array[$new_angst_id] = 0;

            setcookie('bookmarks', json_encode($bookmarks_array), time()+60*60*24*30);

            $this->player->bookmarks[$new_angst_id] = 0;

            $this->player->update_player(true);

        }


        header("Location: index.php?page=index&show=unapproved");
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
    public function construct() {
        $this->initiate("skin_angst_index");

        $code_index = $this->index_switch();
        
        return $code_index;
    }
    
}

?>
