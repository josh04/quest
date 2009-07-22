<?php
/**
 * Description of code_database
 * (DONE) merge this and upgrade_database so there's only one set of bliddy table definitions. sloppy.
 *
 * @author josh04
 * @package code_install
 */
class code_database extends _code_install {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_install");

        $code_database = $this->database_switch();

        parent::construct($code_database);
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
            $message = $this->skin->lang_error->no_database_username;
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if (!$_POST['db_name']) {
            $message = $this->skin->lang_error->no_database_name;
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if (!$_POST['db_server']) {
            $message = $this->skin->lang_error->no_database_server;
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if (!$_POST['db_password'] || !$_POST['db_password_confirm']) {
            $message = $this->skin->lang_error->no_password;
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        if ($_POST['db_password'] != $_POST['db_password_confirm']) {
            $message = $this->skin->lang_error->passwords_do_not_match;
            $setup_database = $this->setup_database_form($message);
            return $setup_database;
        }

        $this->config = array('server' => $_POST['db_server'],
                        'db_username' => $_POST['db_username'],
                        'db_password' => $_POST['db_password'],
                        'database' => $_POST['db_name']);

        $this->make_db();
        
        if ($this->db->IsConnected()) {
            $setup_database = $this->success();
            return $setup_database;
        } else {
            $setup_database = $this->setup_database_form($verified);
            return $setup_database;
        }

    }

   /**
    * okay, let's jam that db in there
    *
    * @return string html
    */
    function success() {

        $make_tables_success = $this->db->execute($this->cron_query.$this->blueprints_query.$this->help_query.$this->items_query.
            $this->mail_query.$this->news_query.$this->players_query.$this->skins_query.$this->tickets_query.$this->log_query.$this->help_insert_query.
            $this->cron_insert_query.$this->pages_query.$this->pages_insert_query.$this->settings_query.$this->settings_insert_query.$this->quests_query);

        if (!$this->db->ErrorMsg()) {
            
            $config_string = "<? \n
                \$config['server'] = '".$_POST['db_server']. "';\n
                \$config['database'] = '".$_POST['db_name']."';\n
                \$config['db_username'] = '".$_POST['db_username']."';\n
                \$config['db_password'] = '".$_POST['db_password']."';\n
                    ?>";
            $config_file = fopen("config.php", 'w');
            fwrite($config_file, $config_string);
            fclose($config_file);

                           
            $success = $this->setup_database_complete();
            return $success;
        } else {
            $message = $this->skin->lang_error->database_create_error;
            $success = $this->setup_database_page($message." ".$this->db->ErrorMsg());
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
