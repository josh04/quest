<?php
/**
 * facebook setup
 *
 * @author josh04
 * @package code_facebook
 */
class code_facebook_index extends _code_facebook {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_facebook_index");

        $code_facebook = $this->facebook_switch();

        parent::construct($code_facebook);
    }

   /**
    * decides which action
    *
    * @return string html
    */
    public function facebook_switch() {
        $facebook_switch = $this->facebook_display();
        return $facebook_switch;
    }

   /**
    * shows the main page, facebook-style
    *
    * @return string html
    */
    public function facebook_display() {

        if (isset($_GET['start'])) {
            $limit = intval($_GET['start']).",10";
        } else {
            $limit = "0,10";
        }

        $angst_query = $this->db->execute("SELECT * FROM `angst` ORDER BY `time` DESC LIMIT ".$limit);

        if ($angst_query->NumRows == 0) {
            unset($angst_query);
            $angst_query = $this->db->execute("SELECT * FROM `angst` ORDER BY `time` DESC LIMIT 0,10");
        }

        $count_query = $this->db->execute("SELECT COUNT(*) AS `c` FROM `angst`");
        $count = $count_query->fetchrow();

        $paginate = $this->paginate($count['c'], intval($_GET['start']), 10);


        $angst_html = "";
        $angst_ids = array();
        $angst_array = array();
        while ($angst = $angst_query->fetchrow()) {
                $angst_ids[] = $angst['id'];
                $angst_array[$angst['id']] = $angst;
        }

        $replies_count_query = $this->db->execute("SELECT `angst_id`, COUNT(*) AS 'c' FROM `angst_replies`
            GROUP BY `angst_id`", array());

        while ($count = $replies_count_query->fetchrow()) {
            $reply_count[$count['angst_id']] = $count['c'];
        }

        $angst_return = "";

        foreach ($angst_array as $id => $single_angst) {
            if ($this->player->is_member) {
                $reply_form = $this->skin->reply_box($this->skin->reply_form($id), $replies[$id]);
            } else {
                $reply_form = $this->skin->reply_box($this->skin->reply_form_guest($id), $replies[$id]);
            }
            if (!$reply_count[$id]) {
                $reply_count[$id] = 0;
            }

            if ($single_angst['type']) {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'glee', 'g', $reply_count[$id]);
            } else {
                $angst_html .= $this->skin->angst($single_angst, $reply_form, 'angst', 'r', $reply_count[$id]);
            }
        }

        return $angst_html.$paginate;



        $facebook_display = $this->skin->facebook_display($this->facebook_id);
        return $facebook_display;
    }
}
?>
