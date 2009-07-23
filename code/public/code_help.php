<?php
/**
 * Help page
 *
 * @author grego
 * @package code_public
 */
class code_help extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_help");

        $code_help = $this->help();

        parent::construct($code_help);
    }

   /**
    * gets skin
    *
    * @return string html
    */
    public function help() {
	if(isset($_GET['id']) && is_numeric($_GET['id'])) $help_id = $_GET['id']; else $help_id = 1;

        $helpq = $this->db->execute("SELECT * FROM `help` WHERE `id`=?",array($help_id));
        while($helpr = $helpq->fetchrow()) {
                $help_html .= $this->skin->help_row($helpr, $help_id, $this->page);
        }

        $helpq = $this->db->execute("SELECT * FROM `help` WHERE `parent`=?",array($help_id));
        while($helpr = $helpq->fetchrow()) {
                $help_children .= $this->skin->help_row($helpr, $help_id, $this->page);
        }

        $help = $this->skin->help($help_html, $help_children);

        return $help;
    }

   /**
    * m-m-m-menu time!
    *
    * @param code_menu $menu menu object
    * @param string $label the name of our slot
    */
    public static function code_guesthelp_menu(&$menu, $label) {
        require_once("skin/public/skin_help.php");
        $online_query = $menu->db->execute("SELECT count(*) AS c FROM players WHERE (last_active > (".(time()-(60*15))."))");
        $online_count = $online_query->fetchrow();
        $money_query = $menu->db->execute("SELECT sum(`gold`) AS g FROM players");
        $money_sum = $money_query->fetchrow();
        
        $menu->bottom .= skin_help::make_guest_stats($online_count['c'], $money_sum['g']);

        return $label;
    }

}
?>