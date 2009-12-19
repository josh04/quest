<?php
/**
 * Description of skin_pages
 *
 * @author josh04
 * @author booher
 * @package skin_admin
 */
class skin_pages extends _skin_admin {
   /**
    * A single page details.
    *
    * @param string $page page code name
    * @param string $redirect page aliases
    * @param string $mod
    * @return string html
    */
    public function page_row($page, $redirect, $mod, $id) {
        $page_row = "<tr>
                <td>".$page."</td>
                <td>".$redirect."</td>
                <td>".$mod."</td>
                <td><a href='index.php?section=admin&amp;page=pages&amp;action=mod&amp;id=".$id."' />
                    <img src='images/icons/pencil.png' alt='Edit' name='Edit' /></a></td>
                <td><form method='POST' action='index.php?section=admin&amp;page=pages&amp;action=uninstall'>
                <input type='hidden' name='id' value='".$id."' />
                <input type='image' src='images/icons/pencil_go.png' alt='Uninstall' name='Uninstall' /></form></td>
            </tr>";
        return $page_row;
    }

   /**
    * uninstall a page row
    *
    * @param string $page page name
    * @return string html
    */
    public function uninstalled_page_row($page, $section) {
        $page_row = "<tr>
            <td>".$page."</td>
            <td>".$type."</td>
            <td>
            <form method='POST' action='index.php?section=admin&amp;page=pages&amp;action=install'>
            <input type='hidden' value='".$page."' name='path' />
            <input type='hidden' value='".$section."' name='new_section' />
            <input type='image' src='images/icons/pencil_add.png' alt='add' name='add' /></form></td>
            <td>
            <form method='POST' action='index.php?section=admin&amp;page=pages&amp;action=delete_page'>
            <input type='hidden' value='".$page."' name='path' />
            <input type='hidden' value='".$section."' name='new_section' />
            <input type='image' src='images/icons/pencil_delete.png' alt='delete' name='delete' />
            </form></td>
            </tr>";
        return $page_row;
    }

   /**
    * uninstall page wrapper
    *
    * @param string $section_name name of the section
    * @param string $section_html pages in that section
    * @return string html
    */
    public function uninstalled_wrapper($section_name, $section_html) {
        $section_wrapper = "
            <h2>".$section_name."</h2>
            <table class='nutable'>
            <tbody>
            <tr>
            <th>Page</th>
            <th>Type</th>
            <th>Install</th>
            <th>Delete</th>
            </tr>
            ".$section_html."</tbody></table>";
        return $section_wrapper;
    }

   /**
    * The wrapper for the current section of modules.
    *
    * @param string $section_name name of the section.
    * @param string $section_html pages in the section.
    * @return string html
    */
    public function section_wrapper($section_name, $section_html) {
        $section_wrapper = "
                <h2>".$section_name."</h2>
            <table class='nutable'>
                <tbody>
                    <tr>
                        <th>Page</th>
                        <th>Redirects</th>
                        <th>Mod loaded</th>
                        <th>Edit</th>
						<th>Uninstall</th>
                    </tr>
            ".$section_html."</tbody></table>";
        return $section_wrapper;
    }

   /**
    * Overall page wrapper.
    *
    * @param string $pages_html the pages in their sections.
    * @param string $message error message.
    * @return string html
    */
    public function pages_wrapper($pages_html, $message) {
        $pages_list = $message.$pages_html;
        return $pages_list;
    }

   /**
    * Makes the "Edit your shit" page
    *
    * @param string $page name of the page
    * @param string $section name of the section
    * @param string $mod name of the mod currently in use (?!)
    * @param int $id id of the page
    * @param string $options mods available
    * @param string $current what's picked now?
    * @return string html
    */
    public function mod_edit_page($page, $section, $mod, $id, $options, $current="") {
        $mod_edit_page = "<h2>Edit Mod</h2>
            <div>You are attempting to override class <strong>code_".$page."</strong>, located at:
            <p style='padding-left:20px;'><strong>'code/".$section."/code_".$page.".php'</strong></p></div>
            ".$current."
            <div><form action='index.php?section=admin&amp;page=pages&amp;action=mod_submit' method='POST'>
            <input type='hidden' name='id' value='".$id."' />
            Class name to override with:
            <select name='mod'>
            <option value='0'>None.</option>
            ".$options."
            </select>
            <input type='submit' value='Submit' /></form></div>";
        return $mod_edit_page;
    }

   /**
    * the current line
    *
    * @param string $mod name of the mod
    * @return string html
    */
    public function current_mod($mod) {
        $current_mod = "<div>The class is currently overridden by the class
            <strong>".$mod."</strong>, located at: <p style='padding-left:20px;'><strong>'mod/".$mod.".php'</strong></p></div>";
        return $current_mod;
    }

   /**
    * An option
    *
    * @param string $filename name of the file
    * @return string html
    */
    public function mod_option($filename) {
        $mod_option = "<option value='".$filename."'>".$filename."</option>";
        return $mod_option;
    }

   /**
    * what if there are none?
    *
    * @return string html
    */
    public function no_options() {
        $no_options = "<option value='0'>None available.</option>";
        return $no_options;
    }
}
?>
