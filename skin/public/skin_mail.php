<?php
/**
 * Description of skin_mail
 *
 * @author josh04
 * @package skin_public
 */
class skin_mail extends skin_common {

   /**
    *
    * @param array $mail
    * @return string html
    */
    public function mail_row($mail) {
        $mail_row = "<tr class=\"mail-".($mail['status']==0?"un":"")."read\">
				<td width='5%'><input type='checkbox' name='mail_id[]' onchange='update_multiple_parent(\"mail_check_all\", \"mail_id\");' value='".$mail['id']."' /></td>
				<td width='20%'>
				<a href='index.php?page=profile&amp;id=".$mail['from']."'>".$mail['username']."</a>
				</td>
				<td width='35%'>
				<a href='index.php?page=mail&amp;action=read&amp;id=".$mail['id']."'>".$mail['subject']."</a>
				</td>
				<td width='40%'>".$mail['time']."</td>
				</tr>";

        return $mail_row;
    }

   /**
    * displays a single message
    *
    * @param array $mail message detail
    * @param string $username
    */
    public function read($mail, $username) {
        $read = "
        <h2>Mail</h2>
            <a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose'>New Message</a>

		<table style='width:100%;margin:12px 0px;'>
			<tr><th style='width:15%;'>To:</th><td><a href='index.php?page=profile&amp;id=".$mail['to']."'>".$username."</a></td></tr>
			<tr><th>From:</th><td><a href='index.php?page=profile&amp;id=".$mail['from']."'>".$mail['username']."</a></td></tr>
			<tr><th>Date:</th><td>".$mail['time']."</td></tr>
			<tr><th>Subject:</th><td>".$mail['subject']."</td></tr>
			<tr><td colspan='2' style='border:1px solid #CCC; border-width: 1px 0px; padding:12px;'>".$mail['body']."</td></tr>
		</table>

			<form method='POST' action='index.php?page=mail&amp;action=compose' style='display:inline;'>
			<input type='hidden' name='mail_to' value='".$mail['username']."' />
			<input type='hidden' name='mail_subject' value='".$mail['pre'].$mail['subject']."' />
			<input type='submit' name='reply' value='Reply' />
			</form>
			<form method='POST' action='index.php?page=mail&amp;action=mark_as_unread' style='display:inline;'>
			<input type='hidden' name='mail_id[]' value='".$mail['id']."' />
			<input type='submit' name='mark_as_unread' value='Mark as unread' />
			</form>
			<form method='post' action='index.php?page=mail&amp;action=delete_multiple' style='display:inline;'>
			<input type='hidden' name='mail_id[]' value='".$mail['id']."' />
			<input type='submit' name='delete' value='Delete' />
			</form>";
        return $read;
    }

   /**
    * mail buttons
    *
    * @param string $mail_html some mail rows
    * @param string $message error?
    * @return string html
    */
    public function mail_wrap($mail_html, $message = "") {
        $mail_wrap = "
        <h2>Mail</h2>
        <div style='margin-bottom:6px;'><strong>Inbox</strong> | <a href='index.php?page=mail&amp;action=compose'>New Message</a></div>
        <form method='POST' action='index.php?page=mail&amp;action=multiple' name='inbox'>
        ".($message?"<div class=\"success\">".$message."</div>":"")."
            <table class='nutable' cellspacing='0' cellpadding='4' style='margin-top:6px;'>
                <tr style='background-color:#EEE;'>
                    <th width='5%'><input type='checkbox' name='check_all' id='mail_check_all' onchange='update_multiple(\"mail_id\",this.checked);' /></th>
                    <th width='20%'>From</th>
                    <th width='40%'>Subject</th>
                    <th width='35%'>Date</th>
                </tr>
                ".$mail_html."
            </table>
            With selected: <select name='multiple_action' onchange='this.form.submit()'>
                    <option selected='selected'>Select an action from below</option>
                    <option value='mark_as_unread'>Mark as unread</option>
                    <option value='mark_as_read'>Mark as read</option>
                    <option value='delete_multiple'>Delete</option>
                </select>
		</form>";
        return $mail_wrap;
    }

   /**
    * compose form
    *
    * @param string $to name to
    * @param string $subject subject
    * @param string $body message text
    * @param string $message error?
    * @return string html
    */
    public function compose($to, $subject, $body, $message = "") {
        $compose = "
            <h2>Mail</h2>
            <a href='index.php?page=mail'>Inbox</a> | <strong>New Message</strong>
            <form method='POST' action='index.php?page=mail&amp;action=compose_submit'>
		<table style='width:100%;margin-top:12px;'>
                <tr><td colspan=\"2\">".$message."</td></tr>
                <tr><td style='width:20%;'><label for='mail-to'>To</label></td><td><input type='text' id='mail-to' name='mail_to' value='".$to."' style='width:95%;' /></td></tr>
                <tr><td><label for='mail-subject'>Subject</label></td><td><input type='text' id='mail-subject' name='mail_subject' value='".$subject."' style='width:95%;' /></td></tr>
                <tr><td colspan='2'><textarea name='mail_body' rows='10' style='width:96%;'>".$body."</textarea></td></tr>
                <tr><td colspan='2'><input type='submit' value='Send' /></td></tr>
		</table>
            </form>";
        return $compose;
    }

   /**
    * delete some?
    *
    * @param string $delete_html which to delete
    * @return string html
    */
    public function delete_multiple($delete_html) {
        $delete_multiple = "
                    Are you sure you want to delete these messages?<br /><br />
                    <form method='POST' action='index.php?page=mail&amp;action=delete_multiple_confirm'>
                    ".$delete_html."
                    <input type='submit' value='Delete' />
                    </form>";
        return $delete_multiple;
    }

   /**
    * <input> for a hidden id
    *
    * @param int $mail_id mail id
    */
    public function mail_delete_input($mail_id) {
        $mail_delete_input = "<input type='hidden' name='mail_id[]' value='".$mail_id."' />";
        return $mail_delete_input;
    }

   /**
    * static function for menu row mail messages
    *
    * @param int $id mail id
    * @param string $username username from
    * @param string $subject mail subject
    * @return string html
    */
    public static function mail_row_small($id, $username, $subject) {
        $mail_row_small = "
            <li>
            <a style='background:#FCC;' href='index.php?page=mail&amp;action=read&amp;id=".$id."'>
            <strong>From:</strong> ".$username."<br />
            <strong>Subject:</strong><br /> ".$subject."</a>
            </li>";
        return $mail_row_small;
    }

   /**
    * static function for the menu wrapper
    *
    * @param string $mail_html the mail rows
    * @return string html
    */
    public static function mail_wrap_small($mail_html) {
        $mail_wrap_small = "<div class='left-section'><ul><li class='header'>Mail</li>".$mail_html."</ul></div>";
        return $mail_wrap_small;
    }

}
?>
