<?php
/**
 * Skinning for admin section
 *
 * @author grego
 * @package skin_public
 */
class skin_index extends _skin_admin {
    
   /**
    * indexpage wrapper
    *
    * @param array $quest_html quests in table rows
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function index_wrapper($message='') {
        $index_wrap = "
        <h2>Administration</h2>
        ".$message."
        <div class='explanation' style='margin:12px 0px;'>".$this->lang_error->admin_home."</div>
        <ul>
            <li><a href='index.php?section=admin&page=ticket'>Ticket control</a></li>
            <li style='margin:12px 0px;'><a href='index.php?section=admin&page=blueprints'>Item blueprints</a></li>
            <li><a href='index.php?section=admin&page=quest'>Quest control</a></li>
        </ul>";
        return $index_wrap;
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
                <p><a href='index.php?page=profile&id=".$ticket['player_id']."'>".$ticket['username']."</a>; ".$category."; <a href='index.php?page=ticket&section=admin'>Back to ticket index</a></p>
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
