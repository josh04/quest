<?php
/**
 * Main portal page
 *
 * @author josh04
 * @package code_public
 */
class code_campus extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_campus");

        $code_campus = $this->campus();

        parent::construct($code_campus);
    }

   /**
    * gets skin
    *
    * @return string html
    */
    public function campus() {
        $log_count = $this->db->getone("select count(*) as `count` from `user_log` where `player_id`=? and `status`='unread'", array($this->player->id));

        $campus = $this->skin->campus($this->player->username, $log_count, $this->settings['campus_caption'], $this->settings['campus_welcome']);

        return $campus;
    }

}
?>