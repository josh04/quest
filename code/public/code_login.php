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
            $code_login = $this->login_switch();
        }

        parent::construct($code_login);
    }

   /**
    * calls code_player function
    *
    */
    public function log_out() {
        $this->player->log_out();
        header("Location: index.php?action=logged_out");
    }

   /**
    * switches between login and register
    *
    * @return string html
    */
    public function login_switch() {
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

        if ($_POST['username'] == "") {
            $log_in = $this->skin->index_guest($username, $this->skin->lang_error->please_enter_username, $this->settings['welcometext']);
            return $log_in;
        }

        if ($_POST['password'] == "") {
            $log_in = $this->skin->index_guest($username, $this->skin->lang_error->please_enter_password, $this->settings['welcometext']);
            return $log_in;
        }

        $player = new code_player();
        $player_exists = $player->log_in($_POST['username'], $_POST['password']);

        if (!$player_exists) {
            $log_in = $this->skin->index_guest($username, $this->skin->lang_error->password_wrong, $this->settings['welcometext']);
        }
        
        header("Location: index.php");
        exit;

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

        $registered_player = new code_player();
        $username = htmlentities($_POST['username'], ENT_QUOTES, "utf-8");
        $email = htmlentities($_POST['email'], ENT_QUOTES, "utf-8");
        $success = $registered_player->create_player($username, $_POST['password'], $email);
        
        if (!$success) {
            $register_submit = $this->skin->register($username, $email, $this->skin->error_box($this->skin->lang_error->error_registering));
            return $register_submit;
        }

        // loooooooooool. I'mma leave this here.

        $body = "<strong>Welcome to CHERUB Quest!</strong>
        <p>CHERUB Quest is a online browser game for fans of
        the CHERUB series by Robert Muchamore, but isn't
        affiliated so don't contact him with problems.
        If you have any problems, for that matter, look at the
        Help Page or use the Ticket System to contact an Admin
        (TeknoTom, JamieHD, Commander of One, Josh0-4 and Grego)</p>

        <p>But above all, have fun!</p>";

        $register_submit = $this->skin->index_guest($username, $this->skin->lang_error->registered, $this->settings['welcometext']);
        return $register_submit;

    }

}
?>
