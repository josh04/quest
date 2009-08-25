<?php
/**
 * Description of code_ticket
 *
 * @author grego
 * @package code_admin
 */
class code_messages extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_messages");

        $code_messages = $this->messages_switch();

        parent::construct($code_messages);
    }

    public function messages_switch() {
        if ($_GET['id']) {
            $messages_switch = $this->single( $_GET['id'] );
        } else {
            $messages_switch = $this->search();
        }
        return $messages_switch;
    }

    public function search($message = "") {

        if($_GET['q']) {
            foreach($this->skin->lang_error as $a=>$l) {
                if(substr_count($a, $_GET['q']) || substr_count($l, $_GET['q']))
                    $search_html .= $this->skin->search_row($a, $l);
            }
            $search_string = "You searched for <strong>".$_GET['q']."</strong>.";
        }
        if(empty($search_html))
            $search_html = "<tr><td colspan='3'><h3>No results</h3></td></tr>";

        $search = $this->skin->search($search_html, $search_string, $message);
        return $search;
    }

    public function single($id) {

        if(!isset($this->skin->lang_error->$_POST['name'])) {
            $message = $this->skin->error_box($this->skin->lang_error->couldnt_find_message);
            $search = $this->skin->search("<tr><td colspan='3'><h3>No results</h3></td></tr>", "", $message);
            return $search;
        }


        if($_POST['name']!="" && isset($_POST['value'])) {
            if(!isset($this->skin->lang_error->$_POST['name'])) {
                $message = $this->skin->error_box($this->skin->lang_error->couldnt_find_message);
            } else {
                // Save it
                $message = $this->skin->error_box($this->skin->lang_error->message_saved);
            }
        }

        $single = $this->skin->single_message($id, $this->skin->lang_error->$id, $message);
        return $single;
    }
}
?>
