<?php
/**
 * sort of roughly get a quest db from an ezrpgdb (e-zur-puh-ger-duh-buh).
 *
 * (DONE) This shares a lot of code with code_database, but I have no way of merging them.
 *
 * @author josh04
 * @package code_install
 */
class code_upgrade_database extends _code_install {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_install");

        $code_upgrade_database = $this->upgrade_switch();

        parent::construct($code_upgrade_database);
    }

   /**
    * display the "you sure?" page, or actually do it
    *
    * @return string html
    */
    public function upgrade_switch() {
        if ($_GET['action'] == 'upgrade') {
            $upgrade_switch = $this->upgrade();
            return $upgrade_switch;
        }

        $upgrade_switch = $this->upgrade_page();
        return $upgrade_switch;
    }

   /**
    * show a message detailing what will happen.
    *
    * @return string html
    */
    public function upgrade_page($message="") {
        $upgrade_page = $this->skin->upgrade_page($message);
        return $upgrade_page;
    }

   /**
    * get the config file and convert it, go from there
    *
    * @return string html
    */
    public function upgrade() {
        
        require("config.php");

        if (!$db) {
            $message = $this->skin->lang_error->upgrade_no_db_object;
            $upgrade = $this->upgrade_page($message);
            return $upgrade;
        }

        $this->config = array('server' => $config_server,
                        'db_username' => $config_username,
                        'db_password' => $config_password,
                        'database' => $config_database);

        $this->make_db();

        $this->upgrade_database();
        
        if (!$this->db->ErrorMsg()) {
            rename("config.php", "config.php.bak");
            $config_string = "<?
                /**
                 * config file
                 * stores database username and password
                 *
                 * @package code_install
                 */
                \$config['server'] = '".$config_server. "';
                \$config['database'] = '".$config_database."';
                \$config['db_username'] = '".$config_username."';
                \$config['db_password'] = '".$config_password."';
                define('IS_UPGRADE', true);
                    ?>";
            $config_file = fopen("config.php", 'w');
            fwrite($config_file, $config_string);
            fclose($config_file);

            $upgrade = $this->setup_database_complete();
            return $upgrade;
        } else {
            $message = $this->skin->lang_error->database_create_error;
            $upgrade = $message." ".$this->db->ErrorMsg();
            return $upgrade;
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
