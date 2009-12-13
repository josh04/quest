<?php
/**
 * frontpage_quest hook
 * (TODO) configurable length of time
 * 
 * displays the current quest box on the main page
 * @package code_hooks
 * @author josh04
 */

/**
 * current quest generator
 *
 * @param code_common $common calling page
 * @return string html
 */
function quest($common) {
        preg_match("/\A([0-9]+):([0-9a-z]+)/is", $common->player->quest, $match);
        $skin = $common->page_generation->make_skin('skin_quest');
        if (!$match[1]) {
            return '';
        }

        $quest_query = $this->db->execute("SELECT * FROM `quests` WHERE `id`=?",array($match[1]));

        if ($quest_query->numrows() != 1) {
            return '';
        }
        $quest = $quest_query->fetchrow();
        $quest_box = $skin->frontpage_current_quest($quest_query['title'], $quest_query['author'], $quest_query['description']);
        return $quest_box;
}

?>