<?php
/**
 * Description of skin_pages
 *
 * @author josh04
 * @package skin_admin
 */
class skin_pages extends skin_common {
   /**
    * A single page details.
    *
    * @param string $page page code name
    * @param string $redirect page aliases
    * @param string $section_name
    * @return string html
    */
    public function page_row($page, $redirect, $section_name) {

        $page_row = "<tr>
                <td>".$page."</td>
                <td>".$redirect."</td>
                <td>".$section_name."</td>
            </tr>";
        return $page_row;
    }

   /**
    * The wrapper for the current section of modules.
    *
    * @param string $section_name name of the section.
    * @param string $section_html pages in the section.
    * @return string html
    */
    public function section_wrapper($section_name, $section_html) {
        $section_wrapper = "<tr>
                <th>".$section_name."</th>
            </tr>
            ".$section_html;
        return $section_wrapper;
    }

   /**
    * Overall page wrapper.
    *
    * @param string $pages_html the pages in their sections.
    * @param string $message error message.
    * @return <type>
    */
    public function pages_wrapper($pages_html, $message) {
        $pages_list = "<table>".$pages_html."</table>";
        return $pages_list;
    }
}
?>
