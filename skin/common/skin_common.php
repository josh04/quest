<?php
/**
 * skin_common.class.php
 *
 * Contains static skin elements, such as header html. No functions called or processing done.
 * @package skin_common
 * @author josh04
 */

class skin_common {

   /**
    * Error strings, lots of error strings
    *
    * @var lang_error
    */
    public $lang_error;

   /**
    * Title of the page, for the header
    *
    * @var string
    */
    public $title;

   /**
    * displays error; needs skinning
    *
    * @param string $error error text
    * @return string html
    */
    public function error_page($error) {
        $error_page = "<h2>Error</h2><div class='error'>".$error."</div>
			<p><a href=\"index.php\">Return home</a> | <a href=\"#\" onclick=\"javascript:history.go(-1);return false;\">Back one page</a></p>
			";
        return $error_page;
    }

   /**
    * Makes the top title
    *
    * @param string $site_name the site name
    * @return string the title
    */
    public function title($site_name) {
        $title = $site_name." - ".$this->title;
        return $title;
    }
    
   /**
    * return the static start of the skin
    *
    * @param string $title page title
    * @param string $site_name name of the site
    * @param string $css name of css file
    * @return string html
    */
    public function start_header($title, $site_name, $skin) {
        
        $start_header = "
                    <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'> 
                    <html xml:lang='en' lang='en' xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                    <title>".$title."</title>
                    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                    <link rel='stylesheet' type='text/css' href='./skin/common/" . $skin . "' />
                    <script type='text/javascript' language='JavaScript' src='./skin/common/functions.js'></script>
                    </head>
                    <body>
                    <div id='wrapper'>
                    <div id='header-text'>".$site_name."</div>
                    ";
        
        return $start_header;
    }

    /**
     * glues the left side to the right. yeah.
     * @param string $page page html
     * @return string html
     */
    public function glue($page) {
        $glue = "<div id='right'>
                 <div id='content'>".$page."
                 </div></div>";
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
                            <span style='font-size:1.5em;'>Quest</span><br />
                            <span style='font-weight:normal;'>Based on ezRPG 0.1 by Zeggy</span><br />
                            New code by Justin, Jamie, Josh04 and Grego<br />
                            This version by Josh04 and Grego
                        </div>
                    </div>
                </div>
                </body>
            </html>";
        return $footer;
    }

   /**
    * for making shiny red boxes
    * (TODO) USE THIS EVERYWHERE
    *
    * @param string $message error
    * @return string html
    */
    public function error_box($message) {
        $error_box = "<div class='error'>".$message."</div>";
        return $error_box;
    }

   /**
    * for making shiny green boxes
    *
    * @param string $message what happened?
    * @return string html
    */
    public function success_box($message) {
        $success_box = "<div class='success'>".$message."</div>";
        return $success_box;
    }

   /**
    * generates a little link to a relevant help topic
    *
    * @param string $id the id of the help topic you're pushing for
    * @return string html
    */
    public function popup_help($id) {
        $popup_help = " <a href='#' class='popup-help-link' onclick='popup_help(${id});return false;'>(?)</a>";
        return $popup_help;
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
        $paginate_link = "<a class='paginate-link' href='index.php?section=".$section."&amp;page=".$page."&amp;start=".$page_start.$extra."'>".$page_number."</a>";
        return $paginate_link;
    }

   /**
    * real current paginate link :P
    *
    * @param int $page_number current page number
    * @param int $page_start current offset
    * @param string $section current section
    * @param string $page current page
    * @return string html
    */
    public function paginate_current($page_number, $page_start, $section, $page) {
        $paginate_current = "<a class='paginate-current' href='#'>".$page_number."</a>";
        return $paginate_current;
    }

   /**
    * wraps it up
    *
    * @param string $paginate the links
    * @return string html
    */
    public function paginate_wrap($paginate) {
        $paginate_wrap = "<div class='paginate'>".$paginate."</div>";
        return $paginate_wrap;
    }

}
?>
