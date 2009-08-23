<?php
/**
 * Description of skin_menu_admin
 *
 * @author josh04
 * @package skin_public
 */
class skin_menu_admin extends skin_common {

   /**
    * a single monstorous row of input boxes
    *
    * @param array $menu_entry array straight from the db
    * @return string html
    */
    public function make_menu_entry($menu_entry) {
        $make_menu_entry = "<tr>
                <td style='width:80%;".$menu_entry['disabled']."'>".$menu_entry['label']."</td>
                <td>
                    <a href='index.php?section=admin&amp;page=menu&amp;action=edit&amp;id=".$menu_entry['id']."'><img src='images/icons/pencil.png' alt='edit' /></a>&nbsp;
                    <a href='index.php?section=admin&amp;page=menu&amp;action=remove&amp;id=".$menu_entry['id']."'><img src='images/icons/delete.png' alt='delete' /></a>
                    <a href='index.php?section=admin&amp;page=menu&amp;action=up&amp;id=".$menu_entry['id']."'><img src='images/icons/arrow_up.png' /></a>
                    <a href='index.php?section=admin&amp;page=menu&amp;action=down&amp;id=".$menu_entry['id']."'><img src='images/icons/arrow_down.png' /></a>
                </td>
            </tr>";
        return $make_menu_entry;
    }

   /**
    * category header
    *
    * @param string $category_name what's it called?
    * @param string $category_html all the rows
    * @return string html
    */
    public function menu_category($category_name, $category_html) {
        $menu_category = "<tr><td style='width:80%'><h3>".$category_name."</h3></td>
                <td>
                    <a href='index.php?section=admin&amp;page=menu&amp;action=category_up&amp;category=".$category_name."'><img src='images/icons/arrow_up.png' /></a>
                    <a href='index.php?section=admin&amp;page=menu&amp;action=category_down&amp;category=".$category_name."'><img src='images/icons/arrow_down.png' /></a>
                </td></tr>
            ".$category_html;
        return $menu_category;
    }

   /**
    * header, add row boxes
    *
    * @param string $menu_html rooooows
    * @param string $menu_entry have they tried to make one already?
    * @param string $message error? (TODO) Not every function should have to have $message as a variable. Maybe tag a global variable into skin_common?
    * @return string html
    */
    public function menu_wrap($menu_html, $menu_entry, $message="") {
        $menu_wrap = "<h2>Menu editor</h2>
            ".$message."
            <p>You can edit the menu items below, or add a new item <a href='index.php?section=admin&amp;page=menu&action=edit'>here</a>.</p>
            <table>
            ".$menu_html."
            </table>";
        return $menu_wrap;
    }

   /**
    * edit or add a menu item
    *
    * @param array $item the menu entry being edited
    * @param string $button_text "Save changes" or "Add menu item"?
    * @param string $message have they done something wrong?
    * @return string html
    */
    public function edit($item, $button_text, $message='') {
        $edit = "<h2>Menu editor</h2>
        ".$message."
        <form action='index.php?section=admin&amp;page=menu' method='post'><table>
            <tr><td style='width:25%;'><label for='menu-label'>Label</label></td>
            <td><input type='text' id='menu-label' name='menu-label' style='width:95%;' value='".$item['label']."' /></td></tr>

            <tr><td colspan='2' class='form-explanation'>The text displayed on the menu item</td></tr>

            <tr><td><label for='menu-category'>Category</label></td>
            <td><input type='text' id='menu-category' name='menu-category' style='width:95%;' value='".$item['category']."' /></td></tr>

            <tr><td colspan='2' class='form-explanation'>The category the menu belongs to</td></tr>

            <tr><td><label for='menu-section'>Section</label></td>
            <td><input type='text' id='menu-section' name='menu-section' style='width:95%;' value='".$item['section']."' /></td></tr>

            <tr><td colspan='2' class='form-explanation'>What section is the page in? \"admin\" or \"public\"?</td></tr>

            <tr><td><label for='menu-page'>Page</label></td>
            <td><input type='text' id='menu-page' name='menu-page' style='width:95%;' value='".$item['page']."' /></td></tr>

            <tr><td colspan='2' class='form-explanation'>The id of the page to link to (the \"page=\" bit in the URL)</td></tr>

            <tr><td><label for='menu-extra'>Extra</label></td>
            <td><input type='text' id='menu-extra' name='menu-extra' style='width:95%;' value='".$item['extra']."' /></td></tr>

            <tr><td colspan='2' class='form-explanation'>Anything to append to the link URL</td></tr>

            <tr><td><label for='menu-enabled' style='margin:0;'>Enabled?</label></td>
            <td><input type='checkbox' id='menu-enabled' name='menu-enabled' ".($item['enabled']==1?"checked='checked' ":"")."/></td></tr>

            <tr><td colspan='2' class='form-explanation'></td></tr>

            <tr><td><label for='menu-function' style='margin:0;'>Related function exists?</label></td>
            <td><input type='checkbox' id='menu-function' name='menu-function' ".($item['function']==1?"checked='checked' ":"")."/></td></tr>

            <tr><td colspan='2' class='form-explanation'>Is the a function to show a more detailed label?</td></tr>

            <tr><td><label for='menu-guest' style='margin:0;'>Guest</label></td>
            <td><input type='checkbox' id='menu-guest' name='menu-guest' ".($item['guest']==1?"checked='checked' ":"")."/></td></tr>

            <tr><td colspan='2' class='form-explanation'>Can guests see it?</td></tr>

            <tr><td colspan='2'><input type='hidden' name='menu-id' value='".$item['id']."' /><input type='submit' name='menu-submit' value='".$button_text."' /> or <a href='?section=admin&amp;page=menu'>Cancel</a></td></tr>
        </table></form>
        ";
        return $edit;
    }

   /**
    * I'm sorry Dave, I'm afraid I can't do that
    *
    * @param array $item the menu entry being removed
    * @return string html
    */
    public function remove_confirm($item) {
        $remove = "<h2>Menu editor</h2>
            <p>Are you sure you want to delete <em>".$item['label']."</em>?</p>
            <p><form action='' method='post'>
                <input type='hidden' name='menu-id' value='".$item['id']."' />
                <input type='submit' value='Delete' /> or <a href='index.php?section=admin&amp;page=menu'>Cancel</a>
            </form></p>";
        return $remove;
    }

   /**
    * tiny snippet of code
    *
    * @return string html
    */
    public function disabled() {
        $disabled = "color:#9A9A9B;";
        return $disabled;
    }
}
?>
