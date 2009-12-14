<?php
/**
 * Help page
 *
 * @author grego
 * @package skin_public
 */
class skin_help extends skin_common {
    
   /**
    * help row
    * 
    * @param array $help_topic help topic from database
    * @return string html
    */
    public function help_row($help_topic){
        $help_row = "
        <div class='help-item'><img src='images/icons/help.png' alt='' />
        <a href='index.php?section=public&amp;page=help&amp;id=".$help_topic['id']."'>".$help_topic['title']."</a></div>
        ";
        return $help_row;
    }

   /**
    * help navigation page
    * 
    * @param string $help_html the html to display as navigation
    * @param string $title the title of the category
    * @return string html
    */
    public function help_navigation($help_html, $title) {
        $help = "
        <h2>Help & Support</h2>
        <div class='help-item-top'>
        ".$title."
        </div>
        " . $help_html;
        return $help;
    }

   /**
    * single help page
    * 
    * @param array $help_topic the topic to display
    * @return string html
    */
    public function help_single($title, $body) {
        $help = "
        <h2>Help & Support</h2>
        <div class='help-item-top'>
        ".$title."
        </div>
        <div class='help-item'>
        ".$body."
        </div>";
        return $help;
    }

    public function popup($title, $body) {
        $popup = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
            <html xml:lang='en' lang='en' xmlns='http://www.w3.org/1999/xhtml'>
            <head>
            <title>Help - ".$title."</title>
            <meta http-equiv='content-type' content='text/html; charset=utf-8' />
            <link rel='stylesheet' type='text/css' href='./skin/common/default.css' />
            </head>
            <body>
            <div class='help-item-top'>".$title."</div>
            <div class='help-item'>".$body."</div>
            </body>
            </html>";
        return $popup;
    }

}
?>
