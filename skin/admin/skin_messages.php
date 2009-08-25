<?php
/**
 * Description of skin_ticket
 *
 * @author grego
 * @package skin_public
 */
class skin_messages extends _skin_admin {
    
   /**
    * single search result row
    *
    * @param string $key the key of the matched language string
    * @param string $value the value of the matched language string
    * @return string html
    */
    public function search_row($key, $value) {
        $search_row = "<tr class='row'>
                <td><a href='?section=admin&amp;page=messages&amp;id=".$key."' style='color:#666;text-decoration:none;'>".$key."</a></td>
                <td>".$value."</td>
                </tr>";
        return $search_row;
    }

   /**
    * search box or results
    *
    * @param string $search_html the messages found
    * @param string $search_string "You searched for..." or not
    * @param string $message any response messages
    */
    public function search($search_html, $search_string='', $message='') {
        $search = "
                <h2>Messages</h2>
                ".$message."
                <p><form action='' method='get'>
                <input type='hidden' name='section' value='admin' />
                <input type='hidden' name='page' value='messages' />
                Search messages: <input type='text' name='q' value='".$_GET['q']."' />
                <input type='submit' value='Search' />
                </form></p>
                ".$search_string."
                <table class='nutable' cellpadding='4' cellspacing='0'>
                <tr style='background-color: #EEE;'>
                <th style='width:40%;'>Name</th>
                <th style='width:60%;'>Message</th>
                ".$search_html."
                </table>";
        return $search;
    }

   /**
    * single message wrapper
    *
    * @param string $name the core name of the message
    * @param string $value the current text of the message
    * @param string $message any response messages
    */
    public function single_message($name, $value, $message='') {
        $single = "
                <h2>Message</h2>
                ".$message."
                <p><strong>".$name."</strong></p>
                <form action='?section=admin&page=messages&id=".$name."' method='post'>
                <input type='hidden' name='name' value='".$name."' />
                <textarea style='width:400px;background-color:#F6F6F6;' name='value'>".$value."</textarea>
                <p><input type='submit' value='Save changes' /> or <a href='?section=admin&page=messages'>Cancel</a></p>
                </form>";
        return $single;
    }

}
?>
