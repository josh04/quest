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

        $start_header = "<div id='header-text'>".$site_name."</div>";
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
    
}
?>
