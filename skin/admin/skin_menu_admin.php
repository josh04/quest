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
        $make_menu_entry = "<tr><form action='index.php?section=admin&amp;page=menu&amp;action=modify' method='POST'>
                <td><input type='text' size='12' name='label' value='".$menu_entry['label']."' /></td>
                <td><input type='text' size='10' name='category' value='".$menu_entry['category']."' /></td>
                <td><input type='text' size='8' name='section' value='".$menu_entry['section']."' /></td>
                <td><input type='text' size='8' name='page' value='".$menu_entry['page']."' /></td>
                <td><input type='text' size='10' name='extra' value='".$menu_entry['extra']."' /></td>
                <td><input type='checkbox' name='function' ".$menu_entry['function']." /></td>
                <td><input type='checkbox' name='enabled' ".$menu_entry['enabled']." /></td>
                <td><input type='checkbox' name='guest' ".$menu_entry['guest']." /></td>
                <td><input type='hidden' name='id' value='".$menu_entry['id']."' /><input type='submit' value='Update' /></td>
            </form></tr>";
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
        $menu_category = "<tr><td><h3>".$category_name."</h3></td></tr>
            <tr>
                <th>Label</th>
                <th>Category</th>
                <th>Section</th>
                <th>Page</th>
                <th>Extra</th>
                <th>Func</th>
                <th>On</th>
                <th>Guest</th>
            </tr>".$category_html."";
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
        $menu_wrap = $message."<h3>Add New Menu Entry:</h3><table cellpadding='0' cellspacing='0'><tbody>
            <tr>
                <th>Label</th>
                <th>Category</th>
                <th>Section</th>
                <th>Page</th>
                <th>Extra</th>
                <th>Func</th>
                <th>On</th>
                <th>Guest</th>
            </tr>
            <tr><form action='index.php?section=admin&amp;page=menu&amp;action=add' method='POST'>
                <td><input type='text' size='12' name='label' value='".$menu_entry['label']."' /></td>
                <td><input type='text' size='10' name='category' value='".$menu_entry['category']."' /></td>
                <td><input type='text' size='8' name='section' value='".$menu_entry['section']."' /></td>
                <td><input type='text' size='8' name='page' value='".$menu_entry['page']."' /></td>
                <td><input type='text' size='10' name='extra' value='".$menu_entry['extra']."' /></td>
                <td><input type='checkbox' name='function' ".$menu_entry['function']." /></td>
                <td><input type='checkbox' name='enabled' ".$menu_entry['enabled']." /></td>
                <td><input type='checkbox' name='guest' ".$menu_entry['guest']." /></td>
                <td><input type='submit' value='Add' /></td>
            </form></tr>
            ".$menu_html."</tbody></table>";
        return $menu_wrap;
    }

   /**
    * danger danger will robinson
    *
    * @param string $reorder_items the set of divs with names in them
    * @param string $reorder_javascript the javascript object declarations
    * @param string $reorder_categories_javascript and some more of the same
    * @return string html
    */
    public function reorder_menu($reorder_items, $reorder_javascript, $reorder_categories_javascript) {
        $reorder_menu = "<h3>Reorder Menu</h3><form action='index.php?section=admin&amp;page=menu&amp;action=reorder' method='POST'>
            <div id='reorder_menu_categories' class='reorder_menu_categories'>
                ".$reorder_items."
            </div>
            <input type='submit' value='Reorder' /></form>
            <script type='text/javascript'>
            PlaceHolder = document.createElement('DIV');
            PlaceHolder.style.backgroundColor = 'rgb(225,225,225)';
            PlaceHolder.SourceI = null;
            List_categories = document.getElementById('reorder_menu_categories');

            PlaceHolder.className = 'reorder_menu';

            className = 'reorder_menu';

            ".$reorder_categories_javascript."
            ".$reorder_javascript."</script>";
        return $reorder_menu;
    }

   /**
    * one of our special divs to be dragged and dropped.
    *
    * @param int $id menu item id
    * @param string $label text to be displayed
    * @param int $order where does it come in our sequence?
    * @param int $category_id arbitrary number assigned to categories
    * @return string html
    */
    public function reorder_item($id, $label, $order, $category_id) {
        $reorder_item = "<div id='e".$id."' class='list_".$category_id."' style='height: 30px;top: 0px;left: 0px;'><input type='hidden' name='menu_id[]' value='".$id."' />".$label."</div>
            ";
        return $reorder_item;
    }

   /**
    * makes a javscript object in the right form
    *
    * @param int $id which div?
    * @param int $category_id arbitrary number assigned to categories
    * @return string html
    */
    public function reorder_javascript($id, $category_id) {
        $reorder_javascript = "new dragObject('e".$id."', null, null, null, itemDragBegin, itemMoved, itemDragEnd, false, 'list_".$category_id."', List_".$category_id.");
            ";
        return $reorder_javascript;
    }

   /**
    * same div object, but for big category
    *
    * @param int $category_id arbitrary number assigned to categories
    * @param string $category_name name of category
    * @param string $category_html the menu item divs
    * @param int $height how big?
    * @return string html
    */
    public function reorder_category($category_id, $category_name, $category_html, $height) {
        $reorder_category = "<div id='reorder_menu_".$category_id."' class='reorder_menu' style='height:".$height."px;top: 0px;left: 0px;'><h3>".$category_name."</h3>".$category_html."</div><br />";
        return $reorder_category;
    }

   /**
    * javascript setup stuff
    *
    * @param int $category_id arbitrary number assigned to categories
    * @param string $category_javascript the individual object declarations
    * @return string html
    */
    public function reorder_javascript_category($category_id, $category_javascript) {
        $reorder_javascript_category = "List_".$category_id." = document.getElementById('reorder_menu_".$category_id."');

            PlaceHolder.className = 'list_".$category_id."';

            className = 'list_".$category_id."';
            ".$category_javascript;
        return $reorder_javascript_category;
    }

   /**
    * category javascript objects
    *
    * @param int $category_id arbitrary number assigned to categories
    * @return string html
    */
    public function reorder_categories_object($category_id) {
        $reorder_categories_object = "new dragObject('reorder_menu_".$category_id."', null, null, null, itemDragBegin, itemMoved, itemDragEnd, false, 'reorder_menu', List_categories);
            ";
        return $reorder_categories_object;
    }
}
?>
