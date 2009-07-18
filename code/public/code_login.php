<?php
/**
 * code_login.class.php
 *
 * logs players in and out
 * @author josh04
 * @package code_public
 */
class code_login extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index");
        if($this->player->is_member) {
            $code_login = $this->log_out();
        } else {
            $code_login = $this->register_or_login();
        }

        parent::construct($code_login);
    }

   /**
    * logs the current user out. Destroys, session, backdates cookie.
    *
    * @return string html
    */
    public function log_out() {
        $username = htmlentities($_POST['username'],ENT_QUOTES,'UTF-8');
        session_unset();
        session_destroy();
        setcookie("cookie_hash", NULL, mktime() - 36000000);
        setcookie("user_id", NULL, mktime() - 36000000);
/*        $login_message = "You have logged out.";
        $log_out = $this->skin->index_guest($username, $login_message, $this->settings['welcometext']);*/
        header("location:index.php");
        return $log_out;
    }

   /**
    * switches between login and register
    *
    * @return string html
    */
    public function register_or_login() {
        if ($_GET['action'] == 'register_submit') {
            $register_or_login = $this->register_submit();
        } else if ($_GET['action'] == 'register') {
            $register_or_login = $this->register();
        } else {
            $register_or_login = $this->log_in();
        }

        return $register_or_login;
    }

   /**
    * logs user in
    * (TODO) slighty odd code structure here
    *
    * @return string html
    */
    public function log_in() {
        $username = htmlentities($_POST['username'],ENT_QUOTES,'UTF-8');
        if ($username == "") {
            $login_message = "Please enter a username.";
            $log_in = $this->skin->index_guest($username, $login_message, $this->settings['welcometext']);
        } elseif ($_POST['password'] == "") {
            $login_message = "Please enter your password.";
            $log_in = $this->skin->index_guest($username, $login_message, $this->settings['welcometext']);
        } else {
            $player_query = $this->db->execute("SELECT `id`, `username`, `password`, `login_salt` FROM `players` WHERE `username`=?", array($_POST['username']));
            $login_message = "Incorrect Username/Password.";
            $log_in = $this->skin->index_guest($username, $login_message, $this->settings['welcometext']);
            
            if ($player_query->recordcount() == 0) {
                return $log_in;
            }

            $player_db = $player_query->fetchrow();
            
            /**
             * Sigh. sha1 fails so bad, and there's no easy way to get rid of it which doesn't suck, esp on an upgraded db.
             */
            if ($player_db['password'] == sha1($_POST['password']) && IS_UPGRADE) {

                $player_db['login_salt'] = substr(md5(uniqid(rand(), true)), 0, 5);
                $player_db['password'] = md5($_POST['password'].$player_db['login_salt']);

                $player_update['password'] = $player_db['password'];
                $player_update['login_salt'] = $player_db['login_salt'];

                $player_insert_query = $this->db->AutoExecute('players', $player_update, 'UPDATE', 'id = '.$player_db['id']);
                
            }

            if ($player_db['password'] == md5($_POST['password'].$player_db['login_salt'])) {
                $login_rand = substr(md5(uniqid(rand(), true)), 0, 5);
                $update_player['login_rand'] = $login_rand;
                $update_player['last_active'] = time();
                $player_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$player_db['id']);
                $hash = md5($player_db['id'].$player_db['password'].$login_rand);
                $_SESSION['user_id'] = $player_db['id'];
                $_SESSION['hash'] = $hash;
                setcookie("user_id", $player_db['id'], mktime()+2592000);
                setcookie("cookie_hash", $hash, mktime()+2592000);
                header("Location: index.php");
                exit;
            }
        }
        return $log_in;
    }

   /**
    * displays registration screen
    *
    * @return string html
    */
    public function register() {
        $register = $this->skin->register("", "", "");
        return $register;
    }

   /**
    * registers a user.
    *
    * @return string html
    */
    public function register_submit() {

        $username = htmlentities($_POST['username'],ENT_QUOTES,'UTF-8');
        $email = htmlentities($_POST['email'],ENT_QUOTES,'UTF-8');

        $player_query = $this->db->execute("SELECT id FROM players WHERE username=?", array($_POST['username']));

        if ($username == "") {
            $register_submit = $this->skin->register($username, $email, "You need to fill in your username.");
            return $register_submit;
        } else if (strlen($_POST['username']) < 3) {
            $register_submit = $this->skin->register($username, $email, "Your username must be longer than 3 characters.");
            return $register_submit;
        } else if (!preg_match("/^[-_a-zA-Z0-9]+$/", $_POST['username'])) {
            $register_submit = $this->skin->register($username, $email, "Your username may contain only alphanumerical characters.");
            return $register_submit;
        } else if ($player_query->recordcount() > 0) {
            $register_submit = $this->skin->register($username, $email, "That username has already been used.");
            return $register_submit;
        }


        if (!$_POST['password']) {
            $register_submit = $this->skin->register($username, $email, "You need to fill in your password.");
            return $register_submit;
        } else if ($_POST['password'] != $_POST['password_confirm']) {
            $register_submit = $this->skin->register($username, $email, "You didn't type in both passwords correctly.");
            return $register_submit;
        } else if (strlen($_POST['password']) < 3) {
            $register_submit = $this->skin->register($username, $email, "Your password must be longer than 3 characters.");
            return $register_submit;
        }

        //Check email
        if (!$_POST['email']) {
            $register_submit = $this->skin->register($username, $email, "You need to fill in your email.");
            return $register_submit;
        } else if ($_POST['email'] != $_POST['email_confirm']) {
            $register_submit = $this->skin->register($username, $email, "You didn't type in both email address correctly.");
            return $register_submit;
        } else if (strlen($_POST['email']) < 3) {
            $register_submit = $this->skin->register($username, $email, "Your email must be longer than 3 characters.");
            return $register_submit;
        } else if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
            $register_submit = $this->skin->register($username, $email, "Your email format is wrong.");
            return $register_submit;
        } else {
            $email_query = $this->db->execute("select `id` from `players` where `email`=?", array($_POST['email']));
            if ($email_query->recordcount() > 0) {
                $register_submit = $this->skin->register($username, $email, "That email has already been used. Please create only one account, creating more than one account will cause all your accounts to be deleted.");
                return $register_submit;
            }
        }
        
        $login_salt = substr(md5(uniqid(rand(), true)), 0, 5);

        $player_insert['username'] = $_POST['username'];
        $player_insert['password'] = md5($_POST['password'].$login_salt);
        $player_insert['email'] = $_POST['email'];
        $player_insert['registered'] = time();
        $player_insert['last_active'] = time();
        $player_insert['ip'] = $_SERVER['REMOTE_ADDR'];
        $player_insert['verified'] = 1;
        $player_insert['login_salt'] = $login_salt;

        $player_insert_query = $this->db->AutoExecute('players', $player_insert, 'INSERT');
        if (!$player_insert_query) {
            $register_submit = $this->skin->register($username, $email, "Error registering new user.");
            return $register_submit;
        }
        $player_id = $this->db->Insert_Id();

        $body = "<strong>Welcome to CHERUB Quest!</strong>
        <p>CHERUB Quest is a online browser game for fans of
        the CHERUB series by Robert Muchamore, but isn't
        affiliated so don't contact him with problems.
        If you have any problems, for that matter, look at the
        Help Page or use the Ticket System to contact an Admin
        (TeknoTom, JamieHD, Commander of One, Josh0-4 and Grego)</p>

        <p>But above all, have fun!</p>";

        $mail_query = $this->db->execute("INSERT INTO mail(`to`,`from`,`subject`,`body`,`time`) VALUES (?, '1', 'Welcome to CHERUB Quest!', ?, time() )",array($newmemberid, $body) );

        $register_submit = $this->skin->index_guest($username, "Congratulations! You have successfully registered. You may login to the game now. Enjoy your stay on campus.");
        return $register_submit;

    }

}
?>
