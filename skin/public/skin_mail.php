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
        $mail_row = "<tr>
				<td width='5%'><input type='checkbox' name='mail_id[]' value='".$mail['id']."' /></td>
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
    */
    public function read($mail, $username) {
        $read = "
            <a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose'>New Message</a>

			<b>To:</b><a href='index.php?page=profile&amp;id=".$mail['to']."'>".$username."</a>
			<b>From:</b><a href='index.php?page=profile&amp;id=".$mail['from']."'>".$mail['username']."</a>
			<b>Date:</b>".$mail['time']."
			<b>Subject:</b>".$mail['subject']."
			<b>Body:</b>".$mail['body']."

			<br /><br />

			<form method='POST' action='index.php?page=mail&amp;action=compose'>
			<input type='hidden' name='mail_to' value='".$mail['username']."' />
			<input type='submit' name='reply' value='Reply' />
			</form>
			<form method='post' action='index.php?page=mail&amp;action=delete_multiple'>
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
        $mail_wrap = "<a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose'>New Message</a>
        <form method='POST' action='index.php?page=mail&amp;action=delete_multiple' name='inbox'>
        ".$message."
            <table width='100%' border='0'>
                <tr>
                    <th width='5%'><input type='checkbox' name='check_all' /></th>
                    <th width='20%'>From</th>
                    <th width='35%'>Subject</th>
                    <th width='40%'>Date</th>
                </tr>
                ".$mail_html."
            </table>
            <input type='submit' name='delete_multiple' value='Delete' />
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
            <a href='index.php?page=mail'>Inbox</a> | <a href='index.php?page=mail&amp;action=compose_submit'>New Message</a>
            <form method='POST' action='index.php?page=mail&amp;action=compose_submit'>
                ".$message."
                To: <input type='text' name='mail_to' value='".$to."' />
                Subject: <input type='text' name='mail_subject' value='".$subject."' />
                Body: <textarea name='mail_body' rows='15' cols='50'>".$body."</textarea>
                <input type='submit' value='Send' />
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

    public static function mail_row_small($id, $username, $subject) {
        $mail_row_small = "
            <li>
            <a style='background:#FCC;' href='index.php?page=mail&amp;action=read&amp;id=".$id."'>
            <strong>From:</strong> ".$username."<br />
            <strong>Subject:</strong><br /> ".$subject."</a>
            </li>";
        return $mail_row_small;
    }

    public static function mail_wrap_small($mail_html) {
        $mail_wrap_small = "<ul><li class='first'>Mail</li>".$mail_html."</ul>";
        return $mail_wrap_small;
    }

}
?>
