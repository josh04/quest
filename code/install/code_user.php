<?php
/**
 * For adding the admin user. Natch.
 *
 * @author josh04
 * @package code_public
 */
class code_user extends code_install {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
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
        $username = htmlentities($_POST['username'], ENT_COMPAT, "UTF-8");
        $user_email = htmlentities($_POST['user_email'], ENT_COMPAT, "UTF-8");
        $user_email_confirm = htmlentities($_POST['user_email_confirm'], ENT_COMPAT, "UTF-8");

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

        $username = htmlentities($_POST['username'],ENT_COMPAT,'UTF-8');
        $email = htmlentities($_POST['email'],ENT_COMPAT,'UTF-8');

        if ($username == "") {
            $register_submit = $this->user_add_form("You need to fill in your username.");
            return $register_submit;
        } else if (strlen($_POST['username']) < 3) {
            $register_submit = $this->user_add_form("Your username must be longer than 3 characters.");
            return $register_submit;
        } else if (!preg_match("/^[-_a-zA-Z0-9]+$/", $_POST['username'])) {
            $register_submit = $this->user_add_form("Your username may contain only alphanumerical characters.");
            return $register_submit;
        }


        if (!$_POST['password']) {
            $register_submit = $this->user_add_form("You need to fill in your password.");
            return $register_submit;
        } else if ($_POST['password'] != $_POST['password_confirm']) {
            $register_submit = $this->user_add_form("You didn't type in both passwords correctly.");
            return $register_submit;
        } else if (strlen($_POST['password']) < 3) {
            $register_submit = $this->user_add_form("Your password must be longer than 3 characters.");
            return $register_submit;
        }

        //Check email
        if (!$_POST['email']) {
            $register_submit = $this->user_add_form("You need to fill in your email.");
            return $register_submit;
        } else if ($_POST['email'] != $_POST['email_confirm']) {
            $register_submit = $this->user_add_form("You didn't type in both email address correctly.");
            return $register_submit;
        } else if (strlen($_POST['email']) < 3) {
            $register_submit = $this->user_add_form("Your email must be longer than 3 characters.");
            return $register_submit;
        } else if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
            $register_submit = $this->user_add_form("Your email format is wrong.");
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


        $player_insert_query = $this->db->AutoExecute('players', $player_insert, 'INSERT');
        if (!$player_insert_query) {
            $insert_user = $this->user_add_form("Error registering new user: ".$this->db->ErrorMsg());
            return $insert_user;
        }

            $lock_string = "<? \n
                    define('IS_INSTALLED', 1);\n
                    ?>";
            $lock_file = fopen("install.lock", 'w');
            fwrite($lock_file, $lock_string);
            fclose($lock_file);

        $insert_user = $this->skin->complete();
        return $insert_user;
    }

}
?>
