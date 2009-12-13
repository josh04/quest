<?php
/**
 * frontpage_mail hook
 * 
 * displays the mail box on the main page
 * @package code_hooks
 * @author josh04
 */

/**
 * mail table function
 *
 * @param code_common $common calling page
 * @return string html
 */
function mail_box($common) {
    $mail_query = $common->db->execute("SELECT `mail`.`id`, `mail`.`from`, `mail`.`status`, `mail`.`subject`, `players`.`username`
                      FROM `mail`
                      INNER JOIN `players` ON `players`.`id` = `mail`.`from`
                      WHERE `to`=? ORDER BY `time` DESC LIMIT 5", array($common->player->id));

    $skin = $common->page_generation->make_skin('skin_mail');
    if (!$mail_query) {
        $mail = $skin->error_box($common->lang->error_getting_mail);
    }

    if ($mail_query->numrows()==0) {
        $mail = $skin->error_box($common->lang->no_messages_long);
    }

    while($mail_row = $mail_query->fetchrow()) {
        if ($mail_row['status']) {
            $mail .= $skin->frontpage_mail($mail_row['id'], $mail_row['username'], $mail_row['subject']);
        } else {
            $mail .= $skin->success_box($skin->mail_entry($mail_row['id'], $mail_row['username'], $mail_row['subject']));
        }
    }

    $mail_box = $skin->frontpage_mail_wrap($mail);
    return $mail_box;
}

?>