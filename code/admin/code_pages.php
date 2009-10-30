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
    public function construct($code_other = "") {
        if ($code_other) {
             parent::construct($code_other);
             return;
        }  
        $this->initiate("skin_pages");

        $code_pages = $this->pages_switch();

        parent::construct($code_pages);
    }

   /**
    * Function for deciding what is being done this page load.
    *
    * @return string html
    */
    public function pages_switch() {
        if ($_GET['action'] == 'mod') {
            $pages_switch = $this->mod_edit_page();
            return $pages_switch;
        }
        if ($_GET['action'] == 'mod_submit') {
            $pages_switch = $this->mod_submit();
            return $pages_switch;
        }
        $pages_switch = $this->pages_list();
        return $pages_switch;
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
            $page_sections[$page['section']] .= $this->skin->page_row($page['name'], $page['redirect'], $page['mod'], $page['id']);
        }

        foreach ($page_sections as $section_name => $section_html) {
            $page_categories_html .= $this->skin->section_wrapper($section_name, $section_html);
        }

        $pages_list = $this->skin->pages_wrapper($page_categories_html, $message);
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
