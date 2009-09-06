<?php
/**
 * code_index.class.php
 *
 * generate the page index
 * @package code_public
 * @author josh04
 */
class code_index extends code_common {
    
    public $player_class = "code_player_profile";
    

   /**
    * builds the logged-in index
    *
    * @param string $message error message
    * @return string html
    */
    public function index_player($message = "") {
        $angst_query = $this->db->execute("SELECT * FROM `angst` ORDER BY `time` DESC");

        $angst_html = "";
        while ($angst = $angst_query->fetchrow()) {
            if ($angst['type']) {
                $angst_html .= $this->skin->glee($angst);
            } else {
                $angst_html .= $this->skin->angst($angst);
            }
        }

        $online_list = $this->online_list();
        $mail = $this->mail();
        
        $index_player = $this->skin->index_player($this->player, $angst_html, $online_list, $mail, $message);
        return $index_player;
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
        
        return implode(", ",$online_list);
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

        return $mail;
    }

   /**
    * builds the guest index. losers :P
    *
    * @return string html
    */
    public function index_guest($login_error) {
        if ($_GET['action'] == "logged_out") {
            $login_error = $this->lang->logged_out;
        }
        $index_guest = $this->skin->index_guest($username, $login_error, $this->settings['welcometext']);
        return $index_guest;
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

        switch($_POST['submit']) {
            case 'Glee':
                $type = 1;
                break;
            case 'Angst':
            default:
                $type = 0;
        }

        $angst_insert['angst'] = $angst;
        $angst_insert['type'] = $type;
        $angst_insert['time'] = time();

        $this->db->AutoExecute('angst', $angst_insert, 'INSERT');

        $code_index = $this->index_player();
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

        if($this->player->is_member) {
            $code_index = $this->index_player();
        } else {
            $code_index = $this->index_guest("", "");
        }

        return $code_index;
    }

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index");

        $code_index = $this->index_switch();

        
        parent::construct($code_index);
    }
    
}

?>
