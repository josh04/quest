<?php
/**
 * Description of code_cron_admin
 *
 * @author josh04
 * @package code_public
 */
class code_cron_admin extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_cron_admin");

        $code_cron_admin = $this->cron_switch();

        parent::construct($code_cron_admin);
    }
    
   /**
    * Decides what to do
    * 
    * @return string html
    */
    public function cron_switch() {
        if ($_POST['cron-edit-id']) {
            $cron_switch = $this->cron_edit_submit();
            return $cron_switch;
        }

        if ($_GET['action'] == 'edit') {
            $cron_switch = $this->cron_edit();
            return $cron_switch;
        }
        $cron_switch = $this->cron_page();
        return $cron_switch;
    }

   /**
    * Makes the list of cron functions
    *
    * @param string $message error tag
    * @return string html
    */
    public function cron_page($message = "") {
        
        $cron_query = $this->db->execute("SELECT * FROM `cron`");

        while ($cron = $cron_query->fetchrow()) {
            $cron['hours'] = intval($cron['period']/3600);
            $cron['minutes'] = intval(($cron['period'] % 3600)/60);
            $cron['seconds'] = intval($cron['period'] % 60);
            $cron['last_active'] = date("F j, Y", $cron['last_active']);
            if ($cron['enabled']) {
                $cron['enabled'] = "Yes";
            } else {
                $cron['enabled'] = "No";
            }
            $cron_html .= $this->skin->cron_row($cron);

        }
        
        $cron_page = $this->skin->cron_page_wrap($cron_html, $message);
        return $cron_page;
    }

   /**
    * if they wanna change how long it takes, i guess
    *
    * @param string $message error message
    * @return string html
    */
    public function cron_edit($message="") {
        if (!$_GET['id']) {
            $cron_edit = $this->cron_page($this->skin->error_box($this->skin->lang_error->no_cron_selected));
            return $cron_edit;
        }

        $cron_query = $this->db->execute("SELECT * FROM `cron` WHERE `id`=?", array(intval($_GET['id'])));

        if (!$cron = $cron_query->fetchrow()) {
            $cron_edit = $this->cron_page($this->skin->error_box($this->skin->lang_error->no_cron_selected));
            return $cron_edit;
        }

        $cron['hours'] = intval($cron['period']/3600);
        $cron['minutes'] = intval(($cron['period'] % 3600)/60);
        $cron['seconds'] = intval($cron['period'] % 60);

        if ($cron['enabled']) {
            $cron['enabled'] = "checked='checked'";
        } else {
            $cron['enabled'] = "";
        }

        $cron_edit = $this->skin->cron_edit($cron, $message);
        return $cron_edit;
    }

   /**
    * sends the new crap to the db
    *
    * @return string html
    */
    public function cron_edit_submit() {
        if (!$_POST['cron-edit-id']) {
            $cron_edit_submit = $this->cron_edit($this->skin->error_box($this->skin->lang_error->no_cron_selected));
            return $cron_edit_submit;
        }

        if (intval($_POST['hours'])) {
            $cron_update_query['period'] = intval($_POST['hours']) * 3600;
        }

        if (intval($_POST['minutes'])) {
            $cron_update_query['period'] = $cron_update_query['period'] + intval($_POST['minutes']) * 60;
        }

        if (intval($_POST['seconds'])) {
            $cron_update_query['period'] = $cron_update_query['period'] + intval($_POST['seconds']);
        }

        if (isset($_POST['enabled'])) {
            $cron_update_query['enabled'] = 1;
        } else {
            $cron_update_query['enabled'] = 0;
        }

        $this->db->AutoExecute("cron", $cron_update_query, "UPDATE", "`id`=".intval($_POST['cron-edit-id']));

        $cron_edit_submit = $this->cron_page($this->skin->success_box($this->skin->lang_error->cron_updated));
        return $cron_edit_submit;
    }

}
?>
