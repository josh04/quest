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
        <input type='text' name='help_title[".$help_topic['id']."]' value='".$help_topic['title']."' />
        <a href='index.php?section=admin&amp;page=help&amp;id=".$help_topic['id']."'>".$help_topic['title']."</a>
        </div>
        ";
        return $help_row;
    }

   /**
    * help navigation page
    * 
    * @param string $help_html the html to display as navigation
    * @param string $help_topic the topic we're playing with
    * @param string $message any message to display
    * @return string html
    */
    public function help_navigation($help_html, $help_topic, $message='') {
        $help = "
        <h2>Help & Support</h2>
        ".$message."
        <form action='' method='post'>
        <input type='hidden' name='form-type' value='help-edit-nav' />
        <input type='hidden' name='id' value='".$help_topic['id']."' />
        <div class='help-item-top'>
        ".$help_topic['title']."
        </div>
        " . $help_html . "
        <input type='submit' name='add' value='Add child topic' />
        <input type='submit' name='save' value='Save changes' />
        or <a href='?section=admin&amp;page=help&amp;id=".$help_topic['parent']."'>back</a>
        </form>";
        return $help;
    }

   /**
    * single help page
    * 
    * @param array $help_topic the topic to display
    * @param string $message how have we done?
    * @return string html
    */
    public function help_single($help_topic, $message='') {
        $help = "
        <h2>Help & Support</h2>
        ".$message."
        <form action='' method='post'>
        <input type='hidden' name='form-type' value='help-edit-single' />
        <input type='hidden' name='id' value='".$help_topic['id']."' />
        <div class='help-item-top'>
        <input type='text' name='title' value='".$help_topic['title']."' />
        </div>
        <div class='help-item'>
        <textarea name='body' style='width:98%;height:200px;'>".$help_topic['body']."</textarea>
        <input type='submit' name='add' value='Add child topic' />
        <input type='submit' name='delete' onclick='return confirm(\"".$this->lang->confirm_help_delete."\");' value='Delete topic' />
        <input type='submit' name='save' value='Save changes' />
        or <a href='?section=admin&amp;page=help&amp;id=".$help_topic['parent']."'>back</a>
        </div>
        </form>";
        return $help;
    }

}
?>
