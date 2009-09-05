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
        <a href='index.php?page=help&amp;id=".$help_topic['id']."'>".$help_topic['title']."</a></div>
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
    public function help_single($help_topic) {
        $help = "
        <h2>Help & Support</h2>
        <div class='help-item-top'>
        ".$help_topic['title']."
        </div>
        <div class='help-item'>
        ".$help_topic['body']."
        </div>";
        return $help;
    }

}
?>
