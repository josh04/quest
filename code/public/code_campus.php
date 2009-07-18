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

        $portal_links = $this->portal_links();
        $campus = $this->skin->campus($this->player->username, $log_count, $this->settings['portal_name'], $this->settings['portal_welcome'], $portal_links);

        return $campus;
    }

   /**
    * creates link section
    *
    * @return string html
    */
    public function portal_links() {
        $l = $this->settings['portal_links'];
        preg_match_all("/(?:\s|\A)(\*+)?(?:[ ]*)(?:([^\n\r|]*)\|)?([^\n\r]+)/is",$l,$matches,PREG_SET_ORDER);
        foreach($matches as $m) {
            if($m[3]=="--") {$portal .= "</td><td>";continue;}
            $m[3] = ($m[2]?"<a href='index.php?page=".$m[2]."'>".$m[3]."</a>":$m[3]);
            $m[3] = ($m[1]=='*'?"<strong>".$m[3]."</strong>":$m[3]);
            $portal .= $m[3] . "<br />\n";
            }
        return $portal;
    }


}
?>