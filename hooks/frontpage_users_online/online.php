<?php
/**
 * frontpage_users_online hook
 * (TODO) configurable length of time
 * 
 * displays the log box on the main page
 * @package code_hooks
 * @author josh04
 */

/**
 * users online generator
 *
 * @param code_common $common calling page
 * @return string html
 */
function online($common) {
        $online_query = $common->db->execute("SELECT id, username FROM players WHERE (last_active > (?))", array((time()-(60*15))));
        $skin = $common->page_generation->make_skin("skin_index", '', 'public');
        $online_list = array();
        while($online = $online_query->fetchrow()) {
            $online_list[] = $skin->frontpage_online_link($online['id'], $online['username']);
        }

        return $skin->frontpage_online_box(implode(", ",$online_list));
}

?>