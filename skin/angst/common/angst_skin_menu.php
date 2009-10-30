<?php
/**
 * Description of skin_menu.php
 *
 * @author josh04
 * @package skin_common
 */
class angst_skin_menu extends skin_common {

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
        $current_menu_entry = "<li class='menu_current'><a href='index.php?section=".$section."&amp;page=".$page.$extra."'>".$label."</a></li>";
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
        $menu_category = "<ul class='menu'>".$category_html."</ul>";
        return $menu_category;
    }
    
   /**
    * all-encompassing div
    *
    * @param string $menu_html all the categories
    * @param string $top top of the menu extras
    * @param string $bottom bottom of the menu extras
    * @return string html
    */
    public function menu_wrap($menu_html, $top, $bottom) {
        $menu_wrap = $top.$menu_html.$bottom;
        return $menu_wrap;
    }
}
?>