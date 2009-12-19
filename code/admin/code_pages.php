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
        if ($_GET['action'] == 'mod') {
            $pages_switch = $this->mod_edit_page();
            return $pages_switch;
        }
        if ($_GET['action'] == 'mod_submit') {
            $pages_switch = $this->mod_submit();
            return $pages_switch;
        }
		if ($_GET['action'] == 'uninstall') {
			$pages_switch = $this->uninstall();
			return $pages_switch;
		}
		if ($_GET['action'] == 'install') {
			$pages_switch = $this->install();
			return $pages_switch;
		}
		if ($_GET['action'] == 'delpage') {
			$pages_switch = $this->deletepg();
			return $pages_switch;
		}
        $pages_switch = $this->deletepg();
        return $pages_switch;
    }
	
	public function deletepg(){
		if(!isset($_GET['id']) || !isset($_GET['type'])){
			$delete=$this->pages_list($this->skin->error_box($this->lang->no_page_selected));
			return $delete;
		}
		if(file_exists("./code/".$_GET['type']."/code_".$_GET['id'].".php")){
			$check=$this->db->execute("select * from `pages` where `name`=?",array($_GET['id']));
			if($check->recordcount() >= 0){
				$uninstall = $this->db->execute("delete from `pages` where `name`=?",array($_GET['id']));
			}
			if(unlink("./code/".$_GET['type']."/code_".$_GET['id'].".php")){
				$delete=$this->pages_list($this->skin->success_box("Deleted!"));
				return $delete;
			}
		}else{
			$delete=$this->pages_list($this->skin->error_box($this->lang->no_page_selected));
			return $delete;
		}
	}
	
	public function install(){
		if(!isset($_GET['id']) || !isset($_GET['type'])){
			$install=$this->pages_list($this->skin->error_box($this->lang->no_page_selected));
			return $install;
		}
		$check=$this->db->execute("select * from `pages` where `name`=?",array($_GET['id']));
		if($check->recordcount() == 0){
			$insert['name'] = $_GET['id'];
			$insert['section'] = $_GET['type'];
			$insert['redirect'] = $_GET['id'];
			$imod = $this->db->autoexecute("pages",$insert,"INSERT");
			if($imod){
			//if (!$this->db->ErrorMsg()) {
				$install=$this->pages_list($this->skin->success_box("Installed!"));
				return $install;
			}
		}else{
			$install=$this->pages_list($this->skin->error_box("Page is already installed!"));
			return $install;
		}
	}
	
	public function uninstall() {
		//$this->initiate();
		if(!isset($_GET['id'])){
			$delmod=$this->pages_list($this->skin->error_box($this->lang->no_page_selected));
			return $delmod;
		}
		$page_query=$this->db->execute("select * from `pages` where `id`=?",array($_GET['id']));
		if($page_query->recordcount() == 1){
			$pquery2=$this->db->execute("delete from `pages` where `id`=?",array($_GET['id']));
			if($pquery2){
				$delmod=$this->pages_list($this->skin->success_box("Uninstalled!"));
				return $delmod;
			}
		}else{
			$delmod=$this->pages_list($this->skin->error_box($this->lang->no_page_selected));
			return $delmod;
		}
		//$query=$db->execute("select * from `pages` where `id`=?");
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
		
		foreach(glob("code/admin/code_*.php") as $phplist){
			$pagename3=str_replace("code/admin/code_","",$phplist);
			$pagename2=str_replace(".php","",$pagename3);
			$query=$this->db->execute("select * from `pages` where `name`=?",array($pagename2));
			if($query->recordcount() == 0){
				$uninsta_section_html .=$this->skin->unins_page_row($pagename2,"admin");
			}
		}
		foreach(glob("code/public/code_*.php") as $phplist){
			$pagename3=str_replace("code/public/code_","",$phplist);
			$pagename2=str_replace(".php","",$pagename3);
			$query=$this->db->execute("select * from `pages` where `name`=?",array($pagename2));
			if($query->recordcount() == 0){
				$uninst_section_html .=$this->skin->unins_page_row($pagename2,"public");
			}
		}
		$page_uninsta =$this->skin->unins_wrapper("Uninstalled Admin Pages",$uninsta_section_html);
		$page_uninst = $this->skin->unins_wrapper("Uninstalled Public Pages",$uninst_section_html);
		$alllists=$page_categories_html.$page_uninsta.$page_uninst;
        $pages_list = $this->skin->pages_wrapper($alllists, $message);
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
