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
    public function help_row($help_topic) {
        $help_row = "
        <div class='help-item'>
        <div style='float:right;'>
            <a href='#' onclick='help_move(\"up\", ".$help_topic['id'].");return false;'><img src='images/icons/arrow_up.png' alt='Up' /></a>
            <a href='#' onclick='help_move(\"down\", ".$help_topic['id'].");return false;'><img src='images/icons/arrow_down.png' alt='Down' /></a>
        </div>
        <img src='images/icons/help.png' alt='' />
        <input type='hidden' name='help_order[".$help_topic['id']."]' id='help_order_".$help_topic['id']."' value='".$help_topic['order']."' />
        <input type='text' name='help_title[".$help_topic['id']."]' value='".$help_topic['title']."' />
        <a href='index.php?section=admin&amp;page=help&amp;id=".$help_topic['id']."'>".$help_topic['title']."</a>
        </div>
        ";
        return $help_row;
    }

   /**
<<<<<<< HEAD:skin/admin/skin_help.php
    * help row for hidden (popup) help pages
    * 
    * @param array $help_topic help topic from database
    * @return string html
    */
    public function help_hidden_row($help_topic) {
        $help_hidden_row = "
        <div class='help-item'>
        <img src='images/icons/help.png' alt='' /> ID: " . $help_topic['id'] . "
        <a href='index.php?section=admin&amp;page=help&amp;id=".$help_topic['id']."'>".$help_topic['title']."</a>
        </div>
        ";
        return $help_hidden_row;
    }

   /**
=======
>>>>>>> d35f7ff95bcba424c44d1bf26964994996615c2a:skin/admin/skin_help.php
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
<<<<<<< HEAD:skin/admin/skin_help.php
    * help navigation page for hidden pages
    * 
    * @param string $help_html the html to display as navigation
    * @param string $help_topic the topic we're playing with
    * @param string $message any message to display
    * @return string html
    */
    public function help_hidden_navigation($help_html) {
        $help_hidden_navigation = "
        <div class='help-item-top' style='margin-top:20px;'>
        Hidden pages
        </div>
        " . $help_html;
        return $help_hidden_navigation;
    }


   /**
=======
>>>>>>> d35f7ff95bcba424c44d1bf26964994996615c2a:skin/admin/skin_help.php
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

<<<<<<< HEAD:skin/admin/skin_help.php
   /**
    * A special case when on the root help files page
    * 
    * @param string $message how have we done?
    * @return string html
    */
    public function help_index($selected, $message='') {
        $help = "
        <h3 style='margin-top:20px;'>General options</h3>
        ".$message."
        <form action='' method='post'>
        <input type='hidden' name='form-type' value='help-edit-index' />
        Help files format: <select name='help_format' style='width: 150px;'>
            <option value='bbcode'".$selected['bbcode'].">BBcode</option>
            <option value='html'".$selected['html'].">HTML</option>
        </select><br />
        <input type='submit' name='save' value='Save changes' />
        </form>";
        return $help;
    }

=======
>>>>>>> d35f7ff95bcba424c44d1bf26964994996615c2a:skin/admin/skin_help.php
}
?>
