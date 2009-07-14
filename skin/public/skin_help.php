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
    * @param integer $help_id id of current topic
    * @return string html
    */
    public function help_row($help_topic, $help_id) {
        $help_row = "
        <a ".($help_topic['id']==$help_id?'':"href='index.php?page=help&id=".$help_topic['id']."' ")."class='help-topic-link'>".$help_topic['title']."</a>
        <div id='help-topic-".$help_topic['id']."' class='help-topic'>".$help_topic['body']."</div>
        ";
        return $help_row;
    }

    
   /**
    * help page
    * 
    * @param string $help_html main code of the page
    * @param string $help_children children of subject
    * @return string html
    */
    public function help($help_html, $help_children) {
        $help = "
        <h2>Help</h2>
        ".$help_html."
".($help_children==''?'':"        <hr style='color:#FFF;' />
        ".$help_children);
        return $help;
    }

}
?>
