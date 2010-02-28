<?php
/**
 * Description of skin_ticket
 *
 * @author grego
 * @package skin_admin
 */
class skin_ticket extends skin_common_admin {

    public $ticket_colour = array("FFFF99",
        "99FF99",
        "FF9999",
        "FFFF99",
        "FFFF99");
    public $ticket_icon = array("images/icons/bug.png",
        "images/icons/accept.png",
        "images/icons/delete.png",
        "images/icons/bug_edit.png",
        "images/icons/bug_link.png");
    
   /**
    * single ticket row
    *
    * @param array $ticket 
    */
    public function ticket_row($ticket) {
        $ticket_row = "<tr class='row' style='background-color:#".$this->ticket_colour[$ticket['status']].";'>
                <td><img src='".$this->ticket_icon[$ticket['status']]."' alt='' /></td>
                <td>".$ticket['id']."</td>
                <td><a href='index.php?page=ticket&section=admin&id=".$ticket['id']."' style='color:#555;text-decoration:none;'>".$ticket['username']."</a></td>
                <td><a href='index.php?page=ticket&section=admin&id=".$ticket['id']."' style='color:#555;text-decoration:none;'>".$ticket['message_short']."</td>
                <td style='color:#333;'>".$ticket['date']."</td></tr>
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
                <th style='width:3%;'>ID</th>
                <th style='width:15%;'>User</th>
                <th style='width:55%;'>Message</th>
                <th style='width:22%;'>Date</th></tr>
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
    * @param array $status_array Array of stati.
    * @param string $message any response messages
    */
    public function single_ticket($ticket, $category, $status_array, $message='') {
        $ticket_wrap = "
                <h2>Ticket ".$ticket['id']."</h2>
                ".$message."
                <p>Issued by <a href='index.php?section=public&amp;page=profile&id=".$ticket['player_id']."'>".$ticket['username']."</a>
                at <strong>".$ticket['date']."</strong></p>
                <p>Status: <select style='width: 150px;background-color:#".$this->ticket_colour[$ticket['status']].";' onChange='window.location=\"index.php?page=ticket&section=admin&id=".$ticket['id']."&status=\"+this.value;'>
                <option".$status_array[0]." style='background:#FFFF99 url(\"images/icons/bug.png\") no-repeat 2px 2px;padding-left:20px;height:20px;' value='0'>Open</option>
                <option".$status_array[1]." style='background:#99FF99 url(\"images/icons/accept.png\") no-repeat 2px 2px;padding-left:20px;height:20px;' value='1'>Resolved</option>
                <option".$status_array[2]." style='background:#FF9999 url(\"images/icons/delete.png\") no-repeat 2px 2px;padding-left:20px;height:20px;' value='2'>Closed</option>
                <option".$status_array[3]." style='background:#FFFF99 url(\"images/icons/bug_edit.png\") no-repeat 2px 2px;padding-left:20px;height:20px;' value='3'>Working</option>
                <option".$status_array[4]." style='background:#FFFF99 url(\"images/icons/bug_link.png\") no-repeat 2px 2px;padding-left:20px;height:20px;' value='4'>Ready</option>
                </select></p>
                <div style='width:400px;height:100px;overflow-y:scroll;background-color:#F6F6F6;padding:4px;border:1px solid #999;'>
                ".$ticket['message']."
                </div>
                <p><a href='#' onClick='showHide(\"replybox\");'>Send a message to the issuer (".$ticket['username'].")</a></p>

                <div id='replybox' style='border:1px solid #999;padding:8px;display:none;'>
                <form action='index.php?page=ticket&section=admin&id=".$ticket['id']."' method='post' name='ticket-mail'>
                <input type='hidden' name='ticket-mail' value='true' />
                <input type='hidden' name='to' value='".$ticket['player_id']."' />
                <input type='hidden' name='subject' value='Response to ticket ".$ticket['id']."' />
                <textarea rows='5' cols='50' name='body' id='ticket-body' style='background-color:#F6F6F6;width:400px;'></textarea><br />
                <input type='submit' value='Send message' /> or <a href='#' onClick='showHide(\"replybox\");'>Cancel</a>

                </div>";

        return $ticket_wrap;
    }

}
?>
