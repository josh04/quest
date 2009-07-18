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
                <td>".substr($quest['description'],0,80).(strlen($quest['description'])>80?"...":"")."</td>
                <td><a onclick='return confirm(\"".$this->lang_error->confirm_delete_quest."\");' href='index.php?section=admin&page=quest&delete=".$quest['id']."'><img src='images/icons/delete.png' alt='Delete quest' /></a></td></tr>";
        return $ticket_row;
    }

   /**
    * quest wrapper
    *
    * @param array $quest_html quests in table rows
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function quest_wrapper($quest_html,$message='') {
        $quest_wrap = "
        <h2>Quests</h2>
        ".$message."
        <p>Currently installed quests:</p>
        <table class='nutable' style='margin:0px;' cellpadding='4' cellspacing='0'>
            <tr style='background-color: #EEE;'>
            <th style='width:5%;'>ID</th>
            <th style='width:10%;'>Author</th>
            <th style='width:20%;'>Title</th>
            <th style='width:60%;'>Description</th>
            <th style='width:5%;'></th></tr>
                ".($quest_html?$quest_html:"<tr><td colspan='4'><h3>There are no quests installed</h3></td></tr>")."
        </table>
        <p>To install a new quest, enter its URL in the box below:<br />
        <form method='get' action=''><input type='hidden' name='section' value='admin' /><input type='hidden' name='page' value='quest' />
        <input type='text' name='url' style='width:70%;' /><input type='submit' value='Install' /></form></p>
        <div class='error'>".$this->lang_error->quest_install_warning."</div>";
        return $quest_wrap;
    }

   /**
    * single ticket wrapper
    *
    * @param array $tickets ticket details
    * @param string $category category of the ticket
    * @param string $message any response messages
    */
    public function single_ticket($ticket,$category,$message='') {
        $sel = array("","","","","");
        $sel[($ticket['status'])] = " selected=\"selected\"";
        $ticket_wrap = "
                <h2>Ticket ".$ticket['id']."</h2>
                ".$message."
                <p><a href='index.php?page=profile&id=".$ticket['player_id']."'>".$ticket['username']."</a>; ".$category."; <a href='index.php?page=ticket&section=admin'>Back to ticket home</a></p>
               <div style='width:300px;height:100px;overflow-y:scroll;background-color:#EEE;padding:4px;border:1px solid #999;'>
                ".$ticket['message']."
                </div>
                Status: <select style='width: 150px;background-color:#".$this->ticket_colour($ticket['status']).";' onChange='window.location=\"index.php?page=ticket&section=admin&id=".$ticket['id']."&status=\"+this.value;'>
                <option".$sel[0]." style='background:#FFFF99 url(\"images/ticket_open.png\") no-repeat 2px 2px;padding-left:16px;height:18px;' value='0'>Open</option>
                <option".$sel[1]." style='background:#99FF99 url(\"images/ticket_resolved.png\") no-repeat 2px 2px;padding-left:16px;height:18px;' value='1'>Resolved</option>
                <option".$sel[2]." style='background:#FF9999 url(\"images/ticket_closed.png\") no-repeat 2px 2px;padding-left:16px;height:18px;' value='2'>Closed</option>
                <option".$sel[3]." style='background:#FFFF99 url(\"images/ticket_working.png\") no-repeat 2px 2px;padding-left:16px;height:18px;' value='3'>Working</option>
                <option".$sel[4]." style='background:#FFFF99 url(\"images/ticket_ready.png\") no-repeat 2px 2px;padding-left:16px;height:18px;' value='4'>Ready</option>
                </select>
                <p><a href='#' onClick='showHide(\"replybox\");'>Send a message to ".$ticket['username']."</a></p>

                <div id='replybox' style='border:1px solid #999;padding:8px;display:none;'>
                <form action='index.php?page=ticket&section=admin&id=".$ticket['id']."' method='post' name='ticket-mail'>
                <input type='hidden' name='ticket-mail' value='true' />
                <input type='hidden' name='to' value='".$ticket['player_id']."' />
                <input type='hidden' name='subject' value='Response to ticket ".$ticket['id']."' />
                <textarea rows='5' cols='50' name='body' id='ticket-body'></textarea><br />
                <input type='submit' value='Send message' /> or <a href='#' onClick='showHide('replybox');'>Cancel</a>

                </div>";

        return $ticket_wrap;
    }

}
?>
