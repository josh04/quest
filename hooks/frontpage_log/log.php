<?php
/**
 * frontpage_log hook
 * 
 * displays the log box on the main page
 * @package code_hooks
 * @author josh04
 */

/**
 * log table function
 *
 * @param code_common $common calling page
 * @return string html
 */
function log_box($common) {
    $log_query = $common->db->execute("SELECT `message`, `status` FROM `user_log` 
        WHERE `player_id`=? ORDER BY `time` DESC LIMIT 5",array($common->player->id));
$this->what();
    $skin = $common->page_generation->make_skin("skin_log", '', 'public');
    
    if (!$log_query) {
        $logs = $skin->error_box($common->lang->error_getting_log);
    }

    if ($log_query->numrows() == 0) {
        $logs = $skin->error_box($common->lang->no_laptop_message);
    }

    while($log_entry = $log_query->fetchrow()) {
        if ($log_entry['status']) {
            $logs .= $skin->frontpage_log($log_entry['message']);
        } else {
            $logs .= $skin->success_box($log_entry['message']);
        }
    }

    $log_box = $skin->frontpage_logs($logs);
    return $log_box;
}

?>