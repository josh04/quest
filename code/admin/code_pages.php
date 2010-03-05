<?php
/**
 * Description of code_pages.php
 *
 * @author josh04
 * @package code_admin
 */
class code_pages extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_pages");

        $code_pages = $this->pages_switch();

        return $code_pages;
    }

   /**
    * Function for deciding what is being done this page load.
    *
    * @return string html
    */
    public function pages_switch() {
        switch ($_GET['action']) {
            case 'mod':
                $pages_switch = $this->mod_edit_page();
                break;
            case 'mod_submit':
                $pages_switch = $this->mod_submit();
                break;
            case 'uninstall':
                $pages_switch = $this->uninstall_page();
                break;
            case 'install':
                $pages_switch = $this->install_page();
                break;
            case 'delete_page':
                $pages_switch = $this->delete_page();
                break;
            default:
                $pages_switch = $this->pages_list();
        }
        return $pages_switch;
    }

   /**
    * deletes a page. like, physically deletes it
    *
    * @return string html
    */
    public function delete_page() {
        $page = str_replace("code/".$_POST['new_section']."/code_", "", $_POST['path']);
        $page = str_replace(".php", "", $path);

        if (!$page || !file_exists("code/".$_POST['new_section']."/code_".$page.".php")) {
            $delete = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $delete;
        }

        $uninstall = $this->db->execute("DELETE FROM `pages` WHERE `name`=?", array($page));

        if (unlink("./code/".$_POST['new_section']."/code_".$page.".php")) {
            $delete = $this->pages_list($this->skin->success_box($this->lang->file_deleted));
        } else {
            $delete = $this->pages_list($this->skin->error_box($this->lang->file_is_read_only));
        }
        return $delete;
    }

   /**
    * installs a page
    *
    * @return string html
    */
    public function install_page() {
        $page = str_replace("code/".$_POST['new_section']."/code_", "", $_POST['path']);
        $page = str_replace(".php", "", $page);
        
        if (!$page || !file_exists("./code/".$_POST['new_section']."/code_".$page.".php")) {
            $delete = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $delete;
        }

        $page_check = $this->db->execute("SELECT * FROM `pages` WHERE `name`=? AND `section`=?", array($page, $_POST['new_section']));

        if ($page_check->recordcount() == 0) {
            $page_insert['name'] = $page;
            $page_insert['section'] = $_POST['new_section'];
            $page_insert['redirect'] = $page;
            $insert_query = $this->db->autoexecute("pages", $page_insert, "INSERT");

            if ($insert_query) {
                $install=$this->pages_list($this->skin->success_box($this->lang->page_installed));
                return $install;
            }
        } else {
            $install = $this->pages_list($this->skin->error_box($this->lang->page_already_installed));
            return $install;
        }
    }

   /**
    * uninstalled a particular page
    *
    * @return string html
    */
    public function uninstall_page() {
        if (!isset($_POST['id'])) {
            $uninstall = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $uninstall;
        }

        $page_query = $this->db->execute("SELECT * FROM `pages` WHERE `id`=?", array($_POST['id']));
        if ($page_query->recordcount() == 1){

            $page_delete_query = $this->db->execute("DELETE FROM `pages` WHERE `id`=?", array($_POST['id']));

            if ($page_delete_query) {
                $uninstall = $this->pages_list($this->skin->success_box($this->lang->page_uninstalled));
                return $uninstall;
            }
        }

        $uninstall = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
        return $uninstall;
    }

   /**
    * Makes the main page-changing menu.
    * (TODO) very similar to the menu code. HMM.
    *
    * @param string $message error message
    * @return string html
    */
    public function pages_list($message="") {
        
        $pages_query = $this->db->execute("SELECT * FROM `pages` ORDER BY `section`");
        
        while ($page = $pages_query->fetchrow()) {
            $page_paths[$page['section']][] = "code/".$page['section']."/code_".$page['name'].".php";
            $page_sections[$page['section']] .= $this->skin->page_row($page['name'], $page['redirect'], $page['mod'], $page['id']);
        }

        foreach ($page_sections as $section_name => $section_html) {
            $page_categories_html .= $this->skin->section_wrapper($section_name, $section_html);
            $uninstall_section = "";

            foreach (glob("code/".$section_name."/code_*.php") as $file_path) {
                if (!in_array($file_path, $page_paths[$section_name]) && $file_path != "code/".$section_name."/code_common_".$section_name.".php") {
                    $uninstall_section .= $this->skin->uninstalled_page_row($file_path, $section_name);
                }
            }

            if ($uninstall_section) {
                $uninstall_categories_html .= $this->skin->uninstalled_wrapper($section_name, $uninstall_section);
            }
        }
        
        $pages_list = $this->skin->pages_wrapper($page_categories_html.$uninstall_categories_html, $message);
        return $pages_list;
    }

   /**
    * Displays the "add mod" page.
    *
    * @return string html
    */
    public function mod_edit_page() {
        if (!isset($_GET['id'])) {
            $mod_edit_page = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $mod_edit_page;
        }

        $page_query = $this->db->execute("SELECT * FROM `pages` WHERE `id`=?", array(intval($_GET['id'])));

        if (!$page = $page_query->fetchrow()) {
            $mod_edit_page = $this->pages_list($this->skin->error_box($this->lang->page_not_found));
            return $mod_edit_page;
        }

        $mod_folder = opendir("mods");
        while ($file = readdir($mod_folder)) {
            if ($file != '.' && $file != '..' && substr($file, -4) == '.php') {
                $options .= $this->skin->mod_option(substr($file, 0, -4));
            }
        }
        closedir($mod_folder);

        if (empty($options)) {
            $options = $this->skin->no_options();
        }

        $page['mod'] = htmlentities($page['mod'], ENT_QUOTES, 'utf-8');
        
        $current = "";
        
        if ($page['mod']) {
            $current = $this->skin->current_mod($page['mod']);
        }

        $mod_edit_page = $this->skin->mod_edit_page($page['name'], $page['section'], $page['mod'], $page['id'], $options, $current);
        return $mod_edit_page;
    }

   /**
    * Does the deed
    *
    * @return string html
    */
    public function mod_submit() {
        if (!isset($_POST['id'])) {
            $mod_edit_page = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $mod_edit_page;
        }

        if ($_POST['mod'] == '0') {
            $page_update['mod'] = "";
            $this->db->AutoExecute("pages", $page_update, "UPDATE", "`id`=".intval($_POST['id']));
            
            $mod_submit = $this->pages_list($this->skin->success_box($this->lang->mod_updated));
            return $mod_submit;
        }

        $mod_folder = opendir("mods");
        while ($file = readdir($mod_folder)) {
            if ($file != '.' && $file != '..') {
                $files[] = substr($file, 0, -4);
            }
        }
        closedir($mod_folder);

        if (!is_array($files)) {
            $mod_edit_page = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $mod_edit_page;
        }
        
        $mod = htmlentities($_POST['mod'], ENT_QUOTES, 'utf-8');

        if (!in_array($mod, $files)) {
            $mod_edit_page = $this->pages_list($this->skin->error_box($this->lang->no_page_selected));
            return $mod_edit_page;
        }
        
        $page_update['mod'] = $mod;
        $this->db->AutoExecute("pages", $page_update, "UPDATE", "`id`=".intval($_POST['id']));

        $mod_submit = $this->pages_list($this->skin->success_box($this->lang->mod_updated));
        return $mod_submit;
    }
}
?>
