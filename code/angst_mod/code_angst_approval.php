<?php
/**
 * Description of code_angst_admin
 *
 * @author josh04
 * @package code_public
 */
class code_angst_approval extends _code_angst_mod {
    public $override_skin = "angst";
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_angst_approval");

        if ($_GET['action'] == 'ajax_approve') {
            $approve = $this->approve($_GET['id']);
            print $approve;
            exit;
        }

        $code_angst_admin = $this->angst_admin_switch();

        return $code_angst_admin;
    }

    public function angst_admin_switch() {
        $message = "";
        if (intval($_GET['id'])) {
            $message = $this->approve(intval($_GET['id']));
        }
        $angst_admin_switch = $this->unapproved_angst_board($message);
        return $angst_admin_switch;
    }

    public function unapproved_angst_board($message = "") {

        if (isset($_GET['start'])) {
            $limit = intval($_GET['start']).",10";
        } else {
            $limit = "0,10";
        }

        $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `approved`=0 ORDER BY `time` DESC LIMIT ".$limit);

        if ($angst_query->NumRows() == 0) {
            unset($angst_query);
            $angst_query = $this->db->execute("SELECT * FROM `angst` WHERE `approved`=0 ORDER BY `time` DESC LIMIT 0,10");
        }

        $count_query = $this->db->execute("SELECT COUNT(*) AS `c` FROM `angst` WHERE `approved`=0");
        $count = $count_query->fetchrow();

        $paginate = $this->paginate($count['c'], intval($_GET['start']), 10);


        $angst_html = "";
        $angst_ids = array();
        $angst_array = array();
        while ($angst = $angst_query->fetchrow()) {
                $angst_ids[] = $angst['id'];
                $angst_array[$angst['id']] = $angst;
        }

        $angst_return = "";

        foreach ($angst_array as $id => $single_angst) {

            $single_angst['date'] = date("jS M, h:i A", $single_angst['time']);

            if ($single_angst['type']) {

                $angst_html .= $this->skin->angst($single_angst, 'glee');
            } else {
                $angst_html .= $this->skin->angst($single_angst, 'angst');
            }
        }
        $unapproved_angst_board = $this->skin->unapproved_angst_board($angst_html.$paginate, $message);
        return $unapproved_angst_board;

    }

   /**
    * Makes an angst appear
    *
    * @param int $id the angst id to approve
    * @return string html
    */
    public function approve($id) {
        $approve_query = $this->db->execute("UPDATE `angst` SET `approved`=1 WHERE `id`=?", array(intval($id)));
        if ($approve_query) {
            return $this->skin->approved(intval($id));
        } else {
            return $this->skin->error_box($this->lang->angst_not_approved);
        }
    }

}
?>