<?php
/**
 * Description of skin_menu.php
 *
 * @author josh04
 * @package skin_common
 */
class skin_menu {

   /**
    * menu entry with section
    *
    * @param string $label text of the menu entry
    * @param string $section which section is it from?
    * @param string $page which page is it?
    * @param string $extra bits which stick on the end.
    * @return string html
    */
    public function public_menu_entry($label, $page, $extra) {
        $public_menu_entry = "<li><a href='index.php?page=".$page.$extra."'>".$label."</a></li>";
        return $public_menu_entry;
    }

   /**
    * menu entry with section
    *
    * @param string $label text of the menu entry
    * @param string $section which section is it from?
    * @param string $page which page is it?
    * @param string $extra bits which stick on the end.
    * @return string html
    */
    public function menu_entry($label, $section, $page, $extra) {
        $menu_entry = "<li><a href='index.php?section=".$section."&amp;page=".$page.$extra."'>".$label."</a></li>";
        return $menu_entry;
    }

   /**
    * menu entry for the current page
    *
    * @param string $label text of the menu entry
    * @param string $section which section is it from?
    * @param string $page which page is it?
    * @param string $extra bits which stick on the end.
    * @return string html
    */
    public function current_menu_entry($label, $section, $page, $extra) {
        $current_menu_entry = "";
        return $current_menu_entry;
    }

   /**
    * menu header bit
    *
    * @param string $category_name category name
    * @param string $category_html the entries themselves
    * @return string html
    */
    public function menu_category($category_name, $category_html) {
        $menu_category = "<div class='left-section'><ul><li class='header'>".$category_name."</li>".$category_html."</div>";
        return $menu_category;
    }
    
}
?>