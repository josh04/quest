<?php
/**
 * Description of skin_menu_admin
 *
 * @author josh04
 * @package skin_public
 */
class skin_menu_admin extends skin_common {

    public function make_menu_entry($menu_entry) {
        $make_menu_entry = "<tr><form action='index.php?section=admin&amp;page=menu&amp;action=modify' method='POST'>
                <td><input type='text' size='12' name='label' value='".$menu_entry['label']."' /></td>
                <td><input type='text' size='10' name='category' value='".$menu_entry['category']."' /></td>
                <td><input type='text' size='8' name='section' value='".$menu_entry['section']."' /></td>
                <td><input type='text' size='8' name='page' value='".$menu_entry['page']."' /></td>
                <td><input type='text' size='10' name='extra' value='".$menu_entry['extra']."' /></td>
                <td><input type='checkbox' name='function' ".$menu_entry['function']." /></td>
                <td><input type='checkbox' name='enabled' ".$menu_entry['enabled']." /></td>
                <td><input type='hidden' name='id' value='".$menu_entry['id']."' /><input type='submit' value='Update' /></td>
            </form></tr>";
        return $make_menu_entry;
    }

    public function menu_category($category_name, $category_html) {
        $menu_category = "<tr><td><h3>".$category_name."</h3></td></tr>
            <tr>
                <th>Label</th>
                <th>Category</th>
                <th>Section</th>
                <th>Page</th>
                <th>Extra</th>
                <th>Func</th>
                <th>On</th>
            </tr>".$category_html."";
        return $menu_category;
    }

    public function menu_wrap($menu_html, $menu_entry, $message="") {
        $menu_wrap = $message."<h3>Add New Menu Entry:</h3><table cellpadding='0' cellspacing='0'><tbody>
            <tr>
                <th>Label</th>
                <th>Category</th>
                <th>Section</th>
                <th>Page</th>
                <th>Extra</th>
                <th>Func</th>
                <th>On</th>
            </tr>
            <tr><form action='index.php?section=admin&amp;page=menu&amp;action=add' method='POST'>
                <td><input type='text' size='12' name='label' value='".$menu_entry['label']."' /></td>
                <td><input type='text' size='10' name='category' value='".$menu_entry['category']."' /></td>
                <td><input type='text' size='8' name='section' value='".$menu_entry['section']."' /></td>
                <td><input type='text' size='8' name='page' value='".$menu_entry['page']."' /></td>
                <td><input type='text' size='10' name='extra' value='".$menu_entry['extra']."' /></td>
                <td><input type='checkbox' name='function' ".$menu_entry['function']." /></td>
                <td><input type='checkbox' name='enabled' ".$menu_entry['enabled']." /></td>
                <td><input type='submit' value='Add' /></td>
            </form></tr>
            ".$menu_html."</tbody></table>";
        return $menu_wrap;
    }
}
?>
