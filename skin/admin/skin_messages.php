<?php
/**
 * Description of skin_ticket
 *
 * @author grego
 * @package skin_admin
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
                <td><a href='index.php?section=admin&amp;page=messages&amp;id=".$key."' style='color:#666;text-decoration:none;'>".$key."</a></td>
                <td>".$value."</td>
                </tr>";
        return $search_row;
    }

   /**
    * one that's already been edited
    *
    * @param string $key the key of the matched language string
    * @param string $value the value of the matched language string
    * @param int $id the database id
    * @return string html
    */
    public function override_row($key, $value, $id) {
        $search_row = "<tr class='row'>
                <td>".$key."</td>
                <td><span style='float:right';width:5%;height:2%;'>
                    <form action='index.php?section=admin&amp;page=messages&amp;action=delete' method='POST'>
                    <input type='hidden' name='override' value='".$id."' />
                    <input type='image' src='images/icons/delete.png' alt='Delete' name='Delete' /></form></span>
                ".$value."
                </td>
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
                <p><form action='index.php?section=admin&amp;page=messages' method='GET'>
                <input type='hidden' name='section' value='admin' />
                <input type='hidden' name='page' value='messages' />
                Search messages: <input type='text' name='search' value='".$search_string."' />
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
                <p><input type='submit' value='Save changes' /> or <a href='index.php?section=admin&page=messages'>Cancel</a></p>
                </form>";
        return $single;
    }

   /**
    * Little message if they searched
    *
    * @param string $search search string
    * @return string html
    */
    public function searched($search) {
        $searched = "You searched for <strong>".$search."</strong>.";
        return $searched;
    }

   /**
    * no results!
    *
    * @return string html
    */
    public function no_results() {
        $no_results = "<tr><td colspan='3'><h3>No results</h3></td></tr>";
        return $no_results;
    }

}
?>
