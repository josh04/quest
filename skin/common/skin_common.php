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
    * return the static start of the skin
    *
    * @param string $title page title
    * @param string $css name of css file
    * @return string html
    */
    public function start_header($title, $skin) {
        
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
                    <div id='header-text'>".$title."</div>
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

}
?>
