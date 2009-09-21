<?php
/**
 * For adding the admin user. Natch.
 *
 * @author josh04
 * @package code_public
 */
class code_user extends _code_install {

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
        $this->initiate("skin_install");
        $this->make_config();
        $this->make_db();

        $code_user = $this->user_switch();

        parent::construct($code_user);
    }

   /**
    * ho hum
    *
    * @return string html
    */
    public function user_switch() {
        if ($_GET['action'] == 'confirm') {
            $database_switch = $this->insert_user();
            return $database_switch;
        }
        $user_switch = $this->user_add_form();
        return $user_switch;
    }

   /**
    * eeets a form
    *
    * @param string $message did they screw up?
    * @return string html
    */
    public function user_add_form($message="") {
        $username = htmlentities($_POST['username'], ENT_QUOTES, "UTF-8");
        $user_email = htmlentities($_POST['user_email'], ENT_QUOTES, "UTF-8");
        $user_email_confirm = htmlentities($_POST['user_email_confirm'], ENT_QUOTES, "UTF-8");

        if($message!="") $message = $this->skin->error_box($message);

        $user_add_form = $this->skin->user_add_form($message, $username, $user_email, $user_email_confirm);
        return $user_add_form;
    }

   /**
    * they have given their details!
    *
    * (TODO) lang_error
    * @return string html
    */
    public function insert_user() {

        $username = htmlentities($_POST['username'],ENT_QUOTES,'UTF-8');
        $email = htmlentities($_POST['email'],ENT_QUOTES,'UTF-8');
        $username_query = $this->db->execute("SELECT * FROM `players` WHERE `username`=?", array($username)); //upgrade

        if ($username == "") {
            $register_submit = $this->user_add_form($this->lang->no_database_username);
            return $register_submit;
        } else if (strlen($_POST['username']) < 3) {
            $register_submit = $this->user_add_form($this->lang->username_not_long_enough);
            return $register_submit;
        } else if (!preg_match("/^[-_a-zA-Z0-9]+$/", $_POST['username'])) {
            $register_submit = $this->user_add_form($this->lang->username_banned_characters);
            return $register_submit;
        } else if ($username_query->numrows() > 0) {
            $register_submit = $this->user_add_form($this->lang->username_conflict);
            return $register_submit;
        }


        if (!$_POST['password']) {
            $register_submit = $this->user_add_form($this->lang->no_password);
            return $register_submit;
        } else if ($_POST['password'] != $_POST['password_confirm']) {
            $register_submit = $this->user_add_form($this->lang->passwords_do_not_match);
            return $register_submit;
        } else if (strlen($_POST['password']) < 3) {
            $register_submit = $this->user_add_form($this->lang->password_not_long_enough);
            return $register_submit;
        }

        //Check email
        if (!$_POST['email']) {
            $register_submit = $this->user_add_form($this->lang->no_email);
            return $register_submit;
        } else if ($_POST['email'] != $_POST['email_confirm']) {
            $register_submit = $this->user_add_form($this->lang->emails_do_not_match);
            return $register_submit;
        } else if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
            $register_submit = $this->user_add_form($this->skin->error_box($this->lang->email_wrong_format));
            return $register_submit;
        }

        $login_salt = substr(md5(uniqid(rand(), true)), 0, 5);

        $player_insert['username'] = $username;
        $player_insert['password'] = md5($_POST['password'].$login_salt);
        $player_insert['email'] = $email;
        $player_insert['registered'] = time();
        $player_insert['last_active'] = time();
        $player_insert['ip'] = $_SERVER['REMOTE_ADDR'];
        $player_insert['rank'] = 'Admin';
        $player_insert['login_salt'] = $login_salt;
        $player_insert['verified'] = 1;


        $player_insert_query = $this->db->AutoExecute('players', $player_insert, 'INSERT');
        if (!$player_insert_query) {
            $insert_user = $this->user_add_form($this->lang->error_registering.$this->db->ErrorMsg());
            return $insert_user;
        }

            $lock_string = "<? \n
                    define('IS_INSTALLED', true);\n
                    ?>";
            $lock_file = fopen("install.lock", 'w');
            fwrite($lock_file, $lock_string);
            fclose($lock_file);

        $insert_user = $this->skin->complete();
        return $insert_user;
    }

   /**
    * gets config info
    *
    * @return array config values
    */
    public function make_config() {
        require("config.php"); // Get config values.
        $this->config = $config;
        return $config;
    }

}
?>
