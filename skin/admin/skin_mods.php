<?php
/**
 * Description of skin_ticket
 *
 * @author grego
 * @package skin_public
 */
class skin_mods extends _skin_admin {
    
   /**
    * single hook for display
    *
    * @param string $title name of the hook
    * @param string $description a summary of what the hook does
    * @param string $author who made it
    * @param string $install is it installed or not?
    * @param string $action an action to perform (eg: edit, install)
    * @return string html
    */
    public function single_hook($title, $description, $author, $install, $action="") {
        $single_hook = "<div class='quest-select'>
                    <div style='float:right;'>
                        ".$install.$action."
                    </div>
                <h3>".$title."</h3>
                <em>By ".$author."</em>
                <p>".$description."</p>
                </div>";
        return $single_hook;
    }

   /**
    * mods home page - displays mods with info and administration links
    *
    * @param string $hooks_html the hooks, formatted
    * @param string $message any feedback
    * @return string html
    */
    public function mods_home($hooks_html, $message="") {
        $mods_home = "<h2>Hooks</h2>
                ".$message."
                ".$hooks_html;
        return $mods_home;
    }

   /**
    * edit link for mods
    *
    * @param string $hook the hook name
    * @return string html
    */
    public function edit_link($hook) {
        $edit_link = "<a href='index.php?section=admin&page=mods&act=edit&id=".$hook."' class='mod-action' style='margin-top:4px;'>".$this->lang->mod_edit."</a>";
        return $edit_link;
    }

   /**
    * install link for mods
    *
    * @param string $hook the hook name
    * @return string html
    */
    public function install_link($hook) {
        $install_link = "<a href='index.php?section=admin&page=mods&act=install&id=".$hook."' class='mod-action' style='margin-top:4px;'>".$this->lang->mod_install."</a>";
        return $install_link;
    }

   /**
    * single mod configuration wrapper
    *
    * @param string $mod_name the name of the mod being configured
    * @param string $config_html the HTML of the configuration setup
    * @param string $message typical feedback entity
    * @return string html
    */
    public function mod_wrapper($mod_name, $config_html, $message="") {
        $mod_wrapper = "
                <h2>Mod configuration: ".$mod_name."</h2>
                ".$message.$config_html;

        return $mod_wrapper;
    }

   /**
    * this mod is installed!
    *
    * @return string html
    */
    public function mod_installed() {
        $mod_installed = "<div class='mod-installed'>".$this->lang->mod_installed."</div>";
        return $mod_installed;
    }

   /**
    * this mod isn't installed!
    *
    * @return string html
    */
    public function mod_not_installed() {
        $mod_not_installed = "<div class='mod-not-installed'>".$this->lang->mod_not_installed."</div>";
        return $mod_not_installed;
    }

   /**
    * installation preface
    *
    * @param string $title title of the mod
    * @param string $description a description of the mod
    * @param string $author who made it
    * @param string $version what version it is
    * @param string $modurl an associated link
    * @param string $install_guide what's going to happen
    * @return string html
    */
    public function install_hook_preface($title, $description, $author, $version, $modurl, $install_guide) {
        $install_hook_preface = "<h2>Hook installation</h2>
            <p>You are installing the following hook:</p>
            <table class='nutable'>
            <tr><th style='width:100px;'>Title</th><td>".$title."</td></tr>
            <tr><th>Author</th><td>".$author."</td></tr>
            <tr><th>Version</th><td>".$version."</td></tr>
            <tr><th>Description</th><td>".$description."</td></tr>
            <tr><th>Mod URL</th><td><a href='".$modurl."' target='_blank'>".$modurl."</a></td></tr>
            </table>
            <p>Installation will involve the following actions:</p>
            <ul>".$install_guide."</ul>
            <p>The developers of Quest can take no responsibility for mods made by other users. You
            are installing this at your own risk.</p>
            <a href='?section=admin&page=mods&act=install&id=frontpage_news&confirmed' class='mod-installed' style='display:table-cell;width:200px;'>I understand, install</a>
            <div style='display:table-cell;width:6px;'></div>
            <a href='?section=admin&page=mods' class='mod-not-installed' style='display:table-cell;width:150px;'>No thanks</a>
";
        return $install_hook_preface;
    }

   /**
    * installation complete screen
    *
    * @param string $title the title of the hook
    * @param string $actions a reassuring list of what's been done
    * @param string $or_admin links to the now configuration panel if it exists
    * @return string html
    */
    public function install_hook($title, $actions, $or_admin) {
        $install_hook = "<h2>Hook installation</h2>
            <p>The hook, <em>".$title."</em>, was successfully installed.</p>
            <div class='dbox'><strong>Activity log</strong>
            ".$actions."
            </div>
            <p>You can now return to the <a href='index.php?section=admin&page=mods'>adminstration mods section</a>".$or_admin.".</p>";
        return $install_hook;
    }

   /**
    * a link to the administration panel of a particular hook
    *
    * @param string $hook the hook's id
    * @return string html
    */
    public function or_admin_link($hook) {
        $or_admin_link = " or <a href='?section=admin&page=mods&act=edit&id=frontpage_news'>configure the mod</a>";
        return $or_admin_link;
    }

}
?>
