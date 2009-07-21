<?php
/**
 * Description of skin_ticket
 *
 * @author grego
 * @package skin_public
 */
class skin_ticket extends _skin_admin {

    public $ticket_color = array("FFFF99",
        "99FF99",
        "FF9999",
        "FFFF99",
        "FFFF99");
    public $ticket_icon = array("images/ticket_open.png",
        "images/ticket_resolved.png",
        "images/ticket_closed.png",
        "images/ticket_working.png",
        "images/ticket_ready.png");
    
   /**
    * single ticket row
    *
    * @param array $ticket 
    */
    public function ticket_row($ticket) {
        $ticket_row = "<tr class='row' style='background-color:#".$this->ticket_colour($ticket['status']).";'>
                <td><img src='".$this->ticket_icon($ticket['status'])."' alt='' /></td><td>".$ticket['id']."</td><td><a href='index.php?page=profile&id=".$ticket['player_id']."'>".$ticket['username']."</a></td>
                <td><a href='index.php?page=ticket&section=admin&id=".$ticket['id']."' style='color:#555;text-decoration:none;'>".substr($ticket['message'],0,30).(strlen($ticket['message'])>30?"...":"")."</td></tr>
                ";
        return $ticket_row;
    }

   /**
    * browse tickets wrapper
    *
    * @param array $tickets ticket details
    * @param string $category category of the ticket
    * @param string $message any response messages
    */
    public function browse_tickets($ticket_html,$message='') {
        $ticket_wrap = "
                <h2>Tickets</h2>
                <table style='border: 1px solid #DDD; margin: 12px 0px; padding: 4px; width: 100%;' cellpadding='4' cellspacing='0'>
                <tr style='background-color: #EEE;'>
                <th style='width:5%;'></th>
                <th style='width:10%;'>ID</th>
                <th style='width:20%;'>User</th>
                <th style='width:65%;'>Message</th></tr>
                ".$ticket_html."
                </table>
                <h3>Key</h3>
                <img src='".$this->ticket_icon[0]."' /> - Open<br />
                <img src='".$this->ticket_icon[1]."' /> - Resolved and responded<br />
                <img src='".$this->ticket_icon[2]."' /> - Closed (No action was necessary)<br />

                <img src='".$this->ticket_icon[3]."' /> - Admin is working on issue<br />
                <img src='".$this->ticket_icon[4]."' /> - Issue resolved but issuer not contacted
                ";
        return $ticket_wrap;
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
                Status: <select style='width: 150px;background-color:#".$this->ticket_colour[$ticket['status']].";' onChange='window.location=\"index.php?page=ticket&section=admin&id=".$ticket['id']."&status=\"+this.value;'>
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
