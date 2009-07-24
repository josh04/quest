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

}
?>