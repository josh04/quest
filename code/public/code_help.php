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

        $help_query = $this->db->execute("SELECT * FROM `help` WHERE `id`=?",array($help_id));
        if($help_query->numrows()==0) {
            $help = $this->skin->error_box($this->lang->help_not_found);
            return $help;
        }
        $help_row = $help_query->fetchrow();

        if($this->settings['help_format']=="bbcode") {
            $help_row['body'] = $this->bbparse($help_row['body'], true);
        }

        $child_query = $this->db->execute("SELECT * FROM `help` WHERE `parent`=? ORDER BY `order` ASC",array($help_id));
        while($child_row = $child_query->fetchrow()) {
                $help_children .= $this->skin->help_row($child_row);
        }

        if($child_query->numrows()==0) {
            $help = $this->skin->help_single($help_row);
        } else {
            $help = $this->skin->help_navigation($help_children, $help_row['title']);
        }

        return $help;
    }

}
?>