<?php
/**
 * Help page
 *
 * @author grego
 * @package code_admin
 */
class code_help extends code_common_admin {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_help");

        // Form handling
        if($_POST['form-type']=="help-edit-nav") {

            if(isset($_POST['add']))
                $message = $this->help_add();

            if(isset($_POST['save']))
                $message = $this->help_nav_save();

        }

        if($_POST['form-type']=="help-edit-single") {

            if(isset($_POST['save']))
                $message = $this->help_single_save();

            if(isset($_POST['add']))
                $message = $this->help_add();

            if(isset($_POST['delete']))
                $message = $this->help_delete();

        }

        if($_POST['form-type']=="help-edit-index") {
            $message = $this->help_index_save();
        }

        $code_help = $this->help($message);

        return $code_help;
    }

   /**
    * adds a new help topic
    *
    */
    public function help_add() {
        $error = !$this->db->Execute("INSERT INTO `help` (`title`, `parent`) VALUES (?,?)",
            array("[untitled]", $_POST['id']));

        if($error)
            $message = $this->skin->error_box($this->lang->error_updating_help);
        else
            $message = $this->skin->success_box($this->lang->help_updated);        

        return $message;
    }

   /**
    * saves a navigation page
    *
    */
    public function help_nav_save() {
        foreach($_POST['help_title'] as $id=>$title) {
           $title = htmlentities($title, ENT_QUOTES, 'utf-8', false);
           $t = $this->db->Execute("UPDATE `help` SET `title`=?, `order`=? WHERE `id`=?",
               array($title, $_POST['help_order'][$id], $id));
           if(!$t) $error = true;
        }
        if($error)
            $message = $this->skin->error_box($this->lang->error_updating_help);
        else
            $message = $this->skin->success_box($this->lang->help_updated);

        return $message;
    }

   /**
    * saves a single help topic
    *
    */
    public function help_single_save() {
        $error = !$this->db->Execute("UPDATE `help` SET `title`=?, `body`=? WHERE `id`=?",
            array($_POST['title'], $_POST['body'], $_POST['id']));

        if($error)
            $message = $this->skin->error_box($this->lang->error_updating_help);
        else
            $message = $this->skin->success_box($this->lang->help_updated);

        return $message;
    }

   /**
    * deletes a single help topic
    *
    */
    public function help_delete() {
        $parent = $this->db->GetOne("SELECT `parent` FROM `help` WHERE `id`=?", array($_POST['id']));

        $this->db->Execute("DELETE FROM `help` WHERE `id`=?", array($_POST['id']));

        header("location:index.php?section=admin&page=help&id=".$parent);
    }

   /**
    * saves the help settings
    *
    */
    public function help_index_save() {
        $error = !$this->settings->set('help_format', $_POST['help_format']);
        return;
    }

   /**
    * gets skin
    *
    * @return string html
    */
    public function help($message) {
	if(isset($_GET['id']) && is_numeric($_GET['id'])) $help_id = $_GET['id']; else $help_id = 1;

        $help_query = $this->db->execute("SELECT * FROM `help` WHERE `id`=?",array($help_id));
        if($help_query->numrows()==0) {
            $help = $this->skin->error_box($this->lang->help_not_found);
            return $help;
        }
        $help_row = $help_query->fetchrow();
        if($help_row['parent']==0) $help_row['parent'] = 1;

        $child_query = $this->db->execute("SELECT * FROM `help` WHERE `parent`=? ORDER BY `order` ASC", array($help_id));
        while($child_row = $child_query->fetchrow()) {
            $help_children .= $this->skin->help_row($child_row);
        }

        if($help_id==1) {
            $hidden_query = $this->db->execute("SELECT * FROM `help` WHERE `parent`=0 AND NOT `id`=1 ORDER BY `id` ASC");  
            while($hidden_row = $hidden_query->fetchrow()) {
                $help_hidden .= $this->skin->help_hidden_row($hidden_row);
            }
        }

        if($child_query->numrows()==0) {
            $help = $this->skin->help_single($help_row, $message);
        } else {
            $help = $this->skin->help_navigation($help_children, $help_row, $message);
            if($help_id==1) {
                $selected[($this->settings->get['help_format'])] = " selected='selected'";
                $help .= $this->skin->help_hidden_navigation($help_hidden);
                $help .= $this->skin->help_index($selected);
            }
        }

        return $help;
    }

}
?>
