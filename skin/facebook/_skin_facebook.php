<?php
/**
 * Hum hum override override
 *
 * @author josh04
 * @package skin_facebook
 */
class _skin_facebook extends skin_common {

   /**
    * return the static start of the skin
    *
    * @param string $title page title
    * @param string $site_name name of the site
    * @param string $css name of css file
    * @return string html
    */
    public function start_header($title, $site_name, $skin) {

        $start_header = "<div id='header-text'>".$site_name."</div>
                    <link rel='stylesheet' type='text/css' href='http://www.atomicfridge.net:12340/angst/skin/angst/common/default.css' />";
        return $start_header;
    }

    /**
     * glues the left side to the right. yeah.
     * @param string $page page html
     * @return string html
     */
    public function glue($page) {
        $glue = "<div id='content'>".$page."</div>";
        return $glue;
    }

    /**
     * returns footer html
     * @return string html
     */
    public function footer() {
         $footer .= "
                    <div id='footer'>
                        <div id='footer-text'>
                            <span style='font-size:1.5em;'>Angst</span><br />
                            This version by Josh04 and Grego.
                        </div>
                    </div>";
        return $footer;
    }

   /**
    * current paginate link
    *
    * @param int $page_number current page number
    * @param int $page_start current offset
    * @param string $section current section
    * @param string $page current page
    * @return string html
    */
    public function paginate_link($page_number, $page_start, $section, $page) {
        $paginate_link = "<fb:action class='paginate_link' href='index.php?section=".$section."&amp;page=".$page."&amp;start=".$page_start.$extra."'>".$page_number."</fb:action>";
        return $paginate_link;
    }

   /**
    * real current paginate link :P
    *
    * @param int $page_number current page number
    * @param string $section current section
    * @param string $page current page
    * @return string html
    */
    public function paginate_current($page_number, $section, $page) {
        $paginate_current = "<fb:action class='paginate_current' href=''>".$page_number."</fb:action>";
        return $paginate_current;
    }

   /**
    * wraps it up
    *
    * @param string $paginate the links
    * @return string html
    */
    public function paginate_wrap($paginate) {
        $paginate_wrap = "<fb:dashboard> ".$paginate."<fb:dashboard> ";
        return $paginate_wrap;
    }
}
?>
