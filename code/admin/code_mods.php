<?php
/**
 * Allows you to access mod configuration settings
 * This is currently mainly about hooks, but I've used the word "mod" because
 * I ultimately intend to combine the both in this page
 *
 * @author grego
 * @package code_admin
 */
class code_mods extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_mods");
        
        $code_mods = $this->mods_switch();

        return $code_mods;
    }

   /**
    * decides what to display
    * 
    * @return string html
    */
    public function mods_switch() {
        $id = $_GET['id'];
        if ($_GET['act'] == "edit" && $id) {
            $mods_switch = $this->single_hook($id);

        // INSTALL
        } else if ($_GET['act'] == "install" && $id) {
            if (isset($_GET['confirmed'])) {
                $mods_switch = $this->install_hook($id);
            } else {
                $mods_switch = $this->install_hook_preface($id);
            }

        // UNINSTALL
        } else if ($_GET['act'] == "uninstall" && $id) {
            if (isset($_GET['confirmed'])) {
                $mods_switch = $this->uninstall_hook($id);
            } else {
                $mods_switch = $this->uninstall_hook_preface($id);
            }

        // HOME
        } else {
            $mods_switch = $this->mods_home();
        }
        return $mods_switch;
    }

   /**
    * displays the mods dashboard
    *
    * @return string html
    */
    public function mods_home($message="") {

        $hooks_folder_location = "./hooks";
        if ($hooks_folder = opendir($hooks_folder_location)) {
            while ($file = readdir($hooks_folder)) {
                if (filetype($hooks_folder_location.'/'.$file)=="dir" && !in_array($file,array('.','..'))) {
                    $hooks[] = $file;
                }
            }
            closedir($hooks_folder);
        }

        if (intval($_GET['id'])) {
            $hooks = array(intval($_GET['id']));
        }

        foreach ($hooks as $hook) {
            $hooks_rows .= $this->parse_hook($hook);
        }

        if (empty($hooks_rows)) {
            $message = $this->skin->error_box($this->lang->mod_not_found);
        }

        return $this->skin->mods_home($hooks_rows, $message);
    }

   /**
    * collects information about a hook and then formats it
    *
    * @param string $hook_name the id of the hook being probed
    * @return string html
    */
    public function parse_hook($hook_name) {
        $path = "./hooks/".$hook_name."/config.xml";

        if (!file_exists($path)) {
            return false;
        }

        $xml = simplexml_load_file($path);

        if ($this->hooks->hook_array) {
            foreach ($this->hooks->hook_array as $hook_type) {
                foreach($hook_type as $hook) {
                    if ($hook[0] == $hook_name) {
                        $install = $this->skin->mod_installed();
                        $installed = true;
                    }
                }
            }
        }

        if (!$installed) {
            $install = $this->skin->mod_not_installed();
            $installed = false;
        }

        if (isset($xml->modurl)) {
            $xml->title = $xml->title . $this->skin->site_link($xml->modurl);
        }

        if (isset($xml->authorurl)) {
            $xml->author = $this->skin->author_link($xml->author, $xml->authorurl);
        }

        if ($installed) {
            if (isset($xml->admin)) {
                $action = $this->skin->edit_link($hook_name);
            }
            $action .= $this->skin->uninstall_link($hook_name);
        } else {
            $action = $this->skin->install_link($hook_name);
        }

        return $this->skin->single_hook($xml->title, $xml->description->long, $xml->author, $install, $action);
    }

   /**
    * shows the configuration page for a particular hook
    *
    * @param string $hook id of the hook to display
    * @return string html
    */
    public function single_hook($hook) {

        $path = "./hooks/".$hook."/config.xml";

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);

        if (!isset($xml->admin)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        ob_start();
            require_once("./hooks/".$hook."/" . $xml->admin->file);
            $single_hook = $this->skin->mod_wrapper($hook, ob_get_contents());
        ob_end_clean();

        return $single_hook;
    }

   /**
    * progressively installs a hook
    *
    * @param string $filename id of the hook we're installing
    * @return string html
    */
    public function install_hook_preface($filename) {

        $path = "./hooks/".$filename."/config.xml";

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);


        foreach ($xml->hook as $hook) {
            $install_guide .= $this->skin->hook_into($hook->attributes());
        }


        if ($xml->settings) {
            foreach($xml->settings->children() as $key=>$value) {
                if ($value) {
                    $key = $key.$this->skin->setting_default($value);
                }
                $install_guide .= $this->skin->setting_register($key);
            }
        }

        if ($xml->sql) {
            $install_guide .= $this->skin->sql_execute($xml->sql);
        }

        $install_hook = $this->skin->install_hook_preface($filename, $xml->title, $xml->description->long, $xml->author, $xml->version, $xml->modurl, $install_guide);
        return $install_hook;
    }

   /**
    * does the actual installing and sends confirmation message
    *
    * @param string $filename id of the hook we're installing
    * @return string html
    */
    public function install_hook($filename) {

        $path = "./hooks/".$filename."/config.xml";

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);

        $handle = fopen("./hooks/index.php", "a");
        foreach ($xml->hook as $hook) {
            $vals = array($filename, strval($hook->file), strval($hook->function));
            if (isset($xml->admin->file)) {
                array_push($vals, strval($xml->admin->file));
            }
            
            $true = fwrite($handle, "\n\$hooks['".$hook->attributes()."'][] = array('".implode("','", $vals)."');");
            
            $returns .= $this->skin->hook_created();
        }
        fclose ($handle);

        if ($xml->settings) {
            foreach($xml->settings->children() as $key=>$value) {
                $this->db->execute("INSERT INTO `settings` (`name`,`value`) VALUES (?,?)", array($key, $value));
                $returns .= $this->skin->setting_registered();
            }
        }

        if ($xml->sql) {
            $this->db->execute($xml->sql);
            $returns .= $this->skin->sql_executed();
        }

        if ($xml->admin->file) {
            $or_admin = $this->skin->or_admin_link($filename);
        }

        $install_hook = $this->skin->install_hook($xml->title, $returns, $or_admin);
        return $install_hook;
    }

   /**
    * does the actual installing and sends confirmation message
    *
    * @param string $hook id of the hook we're installing
    * @return string html
    */
    public function uninstall_hook_preface($hook) {

        $path = "./hooks/".$hook."/config.xml";

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);

        if ($xml->hook) {
            $options .= $this->skin->uninstall_option("hook", $this->lang->mod_uninstall_option_unhook," checked='checked'");
        }

        if ($xml->settings) {
            $options .= $this->skin->uninstall_option("settings", $this->lang->mod_uninstall_option_settings,"");
        }

        if ($xml->sql) {
            $options .= $this->lang->mod_uninstall_option_sql;
        }

        $uninstall_hook = $this->skin->uninstall_hook_preface($hook, $xml->title, $options);
        return $uninstall_hook;

    }

   /**
    * does the actual installing and sends confirmation message
    *
    * @param string $hook id of the hook we're installing
    * @return string html
    */
    public function uninstall_hook($hook) {

        $path = "./hooks/".$hook."/config.xml";

        if (empty($_POST)) {
            $_POST['hook'] == "on";
        }

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);

        if ($xml->hook && $_POST['hook']=="on") {
            $old = file_get_contents("./hooks/index.php");
            $handle = fopen("./hooks/index.php", "w+");
            $vals = array($hook, strval($xml->hook->file), strval($xml->hook->function));
            if (isset($xml->admin->file)) {
                array_push($vals, strval($xml->admin->file));
            }
            $old = str_replace("\r\n\$hooks['".$xml->hook->attributes()."'][] = array('".implode("','", $vals)."');","",$old);
            fwrite($handle, $old);
            $returns .= $this->skin->hook_removed();
        }

        if ($xml->settings && $_POST['settings']=="on") {
            foreach($xml->settings->children() as $key=>$value) {
                $this->db->execute("DELETE FROM `settings` WHERE `name`=?", array($key));
                $returns .= $this->skin->setting_deleted();
            }
        }

        $uninstall_hook = $this->skin->uninstall_hook($xml->title, $returns);
        return $uninstall_hook;

    }
}
?>
