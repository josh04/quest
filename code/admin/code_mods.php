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
        } else if ($_GET['act'] == "install" && $id) {
            if (isset($_GET['confirmed'])) {
                $mods_switch = $this->install_hook($id);
            } else {
                $mods_switch = $this->install_hook_preface($id);
            }
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

        foreach($hooks as $hook) {
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
    * @param string $hook the id of the hook being probed
    * @global $hooks
    * @return string html
    */
    public function parse_hook($hook) {
        global $hooks;

        $path = "./hooks/".$hook."/config.xml";

        if (!file_exists($path)) {
            return false;
        }

        $xml = simplexml_load_file($path);

        if ($hooks) {
            foreach($hooks as $a_hook) {
                if($a_hook[0] == $hook) {
                    $install =  $this->skin->mod_installed();
                    $installed = true;
                }
            }
        }

        if (!$installed) {
            $install = $this->skin->mod_not_installed();
            $installed = false;
        }

        if (isset($xml->authorurl)) {
            $xml->author = "<a href='".$xml->authorurl."'>".$xml->author."</a>";
        }

        if ($installed) {
            if (isset($xml->admin)) {
                $action = $this->skin->edit_link($hook);
            }
        } else {
            $action = $this->skin->install_link($hook);
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
    * @param string $hook id of the hook we're installing
    * @return string html
    */
    public function install_hook_preface($hook) {

        $path = "./hooks/".$hook."/config.xml";

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);

        if ($xml->hook) {
            $install_guide .= "<li>Hook into <em>".$xml->hook->attributes()."</em></li>";
        }

        if ($xml->settings) {
            foreach($xml->settings->children() as $key=>$value) {
                if ($value) {
                    $key = $key." (default: ".$value.")";
                }
                $install_guide .= "<li>Register the setting <em>".$key."</em></li>";
            }
        }

        if ($xml->sql) {
            $install_guide .= "<li>Execute the following SQL:<br /><div class='quest-select'>".$xml->sql."</div></li>";
        }

        $install_hook = $this->skin->install_hook_preface($xml->title, $xml->description->long, $xml->author, $xml->version, $xml->modurl, $install_guide);
        return $install_hook;
    }

   /**
    * does the actual installing and sends confirmation message
    *
    * @param string $hook id of the hook we're installing
    * @return string html
    */
    public function install_hook($hook) {

        $path = "./hooks/".$hook."/config.xml";

        if (!file_exists($path)) {
            return $this->mods_home($this->skin->error_box($this->lang->mod_no_configure));
        }

        $xml = simplexml_load_file($path);

        if ($xml->hook) {
            $handle = fopen("./hooks/index.php", "a");
            $vals = array($hook, strval($xml->hook->file), strval($xml->hook->function));
            if (isset($xml->admin->file)) {
                array_push($vals, strval($xml->admin->file));
            }
            fwrite($handle, "\r\n\$hooks['".$xml->hook->attributes()."'] = array('".implode("','", $vals)."');");
            $returns .= "<br />Hook created";
        }

        if ($xml->settings) {
            foreach($xml->settings->children() as $key=>$value) {
                $this->db->execute("INSERT INTO `settings` (`name`,`value`) VALUES (?,?)", array($key, $value));
                $returns .= "<br />Setting registered";
            }
        }

        if ($xml->sql) {
            $this->db->execute($xml->sql);
            $returns .= "<br />SQL executed";
        }

        if ($xml->admin->file) {
            $or_admin = $this->skin->or_admin_link($hook);
        }

        $install_hook = $this->skin->install_hook($xml->title, $returns, $or_admin);
        return $install_hook;
    }

}
?>
