<?php
/**
 * Description of code_database
 * (DONE) merge this and upgrade_database so there's only one set of bliddy table definitions. sloppy.
 *
 * @author josh04
 * @package code_install
 */
class code_database extends code_common_install {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_install");

        $code_database = $this->database_switch();

        return $code_database;
    }

   /**
    * have they filled it in?
    *
    * @return string html
    */
    public function database_switch() {
        if ($_GET['action'] == 'confirm') {
            $database_switch = $this->setup_database();
            return $database_switch;
        }

        $database_switch = $this->setup_database_form();
        return $database_switch;
    }

   /**
    * show a fill-me-in
    *
    * @param string $message did the fuck up?
    * @return string html
    */
    public function setup_database_form($message="") {
        $db_username = htmlentities($_POST['db_username'], ENT_QUOTES, "UTF-8");
        $db_name = htmlentities($_POST['db_name'], ENT_QUOTES, "UTF-8");
        if ($_POST['db_server']) {
            $db_server = htmlentities($_POST['db_server'], ENT_QUOTES, "UTF-8");
        } else {
            $db_server = "localhost";
        }
        $database_switch = $this->skin->setup_database_form($db_server, $db_username, $db_name, $message);
        return $database_switch;
    }

   /**
    * check all the vars
    *
    * @return string html
    */
    public function setup_database() {
        if (!$_POST['db_username']) {
            $message = $this->skin->error_box($this->lang->no_database_username);
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if (!$_POST['db_name']) {
            $message = $this->skin->error_box($this->lang->no_database_name);
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if (!$_POST['db_server']) {
            $message = $this->skin->error_box($this->lang->no_database_server);
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if (!$_POST['db_password'] || !$_POST['db_password_confirm']) {
            $message = $this->skin->error_box($this->lang->no_password);
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if ($_POST['db_password'] != $_POST['db_password_confirm']) {
            $message = $this->skin->error_box($this->lang->passwords_do_not_match);
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        $this->config = array('server' => $_POST['db_server'],
                        'db_username' => $_POST['db_username'],
                        'db_password' => $_POST['db_password'],
                        'database' => $_POST['db_name']);

        // Try to make the database, but don't error out
        $this->make_db(true);
        
        if ($this->db->IsConnected()) {
            $setup_database = $this->success();
            return $setup_database;
        } else {
            $message = $this->skin->error_box($this->lang->failed_to_connect);
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

    }

   /**
    * okay, let's jam that db in there
    *
    * @return string html
    */
    function success() {

        $make_tables_success = $this->create_database();
        
        if (!$this->db->ErrorMsg()) {
            $config_string = "<? 
                /**
                 * config file
                 * stores database username and password
                 *
                 * @package code_install
                 */
                \$config['server'] = '".$_POST['db_server']. "';
                \$config['database'] = '".$_POST['db_name']."';
                \$config['db_username'] = '".$_POST['db_username']."';
                \$config['db_password'] = '".$_POST['db_password']."';
                define('IS_UPGRADE', false);
                    ?>";
            $config_file = fopen("config.php", 'w');
            fwrite($config_file, $config_string);
            fclose($config_file);

                           
            $success = $this->setup_database_complete();
            return $success;
        } else {
            $message = $this->lang->database_create_error;
            $success = $this->setup_database_form($this->skin->error_box($message." ".$this->db->ErrorMsg()));
            return $success;
        }

    }

   /*
    * well done!
    *
    * @return string html
    */
    public function setup_database_complete() {
        $setup_database_complete = $this->skin->setup_database_complete();
        return $setup_database_complete;
    }

}
?>
