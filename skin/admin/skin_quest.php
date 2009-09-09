<?php
/**
 * Skinning for admin quest section
 *
 * @author grego
 * @package skin_public
 */
class skin_quest extends _skin_admin {
    
   /**
    * single quest row
    *
    * @param array $quest
    * @return string html
    */
    public function quest_row($quest) {
        $ticket_row = "
                <tr><td>".$quest['id']."</td><td>".$quest['author']."</td><td>".$quest['title']."</td>
                <td>".$quest['description_short']."</td>
                <td><a onclick='return confirm(\"".$this->lang->confirm_delete_quest."\");' href='index.php?section=admin&page=quest&delete=".$quest['id']."'><img src='images/icons/delete.png' alt='Delete quest' /></a></td></tr>";
        return $ticket_row;
    }

   /**
    * quest wrapper
    *
    * @param array $quest_html quests in table rows
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function quest_wrapper($quest_html, $code, $message='') {
        $quest_wrap = "
        <h2>Quests</h2>
        <p>Currently installed quests:</p>
        <table class='nutable' style='margin:0px;' cellpadding='4' cellspacing='0'>
            <tr style='background-color: #EEE;'>
            <th style='width:5%;'>ID</th>
            <th style='width:10%;'>Author</th>
            <th style='width:20%;'>Title</th>
            <th style='width:60%;'>Description</th>
            <th style='width:5%;'></th></tr>
                ".$quest_html."
        </table>
            ".$message."
        <form method='get' action=''><p>To install a new quest, enter its URL in the box below:<br />
        <input type='hidden' name='section' value='admin' /><input type='hidden' name='page' value='quest' />
        <input type='text' name='url' style='width:70%;' /><input type='submit' value='Install' /></p></form>

        <form method='get' action=''><p>You can also change your quest security code:<br />
        <input type='hidden' name='section' value='admin' /><input type='hidden' name='page' value='quest' />
        <input type='text' name='code' style='width:70%;' value='".$code."' /><input type='submit' value='Update' /></p></form>

        <div class='error'>".$this->lang->quest_install_warning."</div>";
        return $quest_wrap;
    }

}
?>
