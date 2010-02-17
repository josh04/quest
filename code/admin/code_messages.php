<?php
/**
 * Description of code_ticket
 *
 * @author grego
 * @package code_admin
 */
class code_messages extends code_common_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_messages");

        $code_messages = $this->messages_switch();

        return $code_messages;
    }

    public function messages_switch() {

        if ($_GET['action'] == 'delete') {
            $id = intval($_POST['override']);
            $messages_switch = $this->delete_override($id);
            return $messages_switch;
        }

        if ($_GET['id']) {
            $messages_switch = $this->single( $_GET['id'] );
        } else {
            $messages_switch = $this->search();
        }
        return $messages_switch;
    }

    public function search($message = "") {

        if($_GET['search']) {
            $search = htmlentities($_GET['search'], ENT_QUOTES, 'utf-8');

            foreach($this->lang as $key => $value) {
                if (substr_count($key, $search) || substr_count($value, $search)) {
                    $search_html .= $this->skin->search_row($key, $value);
                }
            }

            $search_string = $this->skin->searched($search);
            if (empty($search_html)) {
                $search_html = $this->skin->no_results();
            }
        }

        if (empty($search_html)) {

            $message_query = $this->db->execute('SELECT * FROM `lang`');

            while ($override = $message_query->fetchrow()) {
                $search_html .= $this->skin->override_row($override['name'], $override['override'], $override['id']);
            }
        }

        $search = $this->skin->search($search_html, $search_string, $message);
        return $search;
    }

    public function single($id) {
        $id = htmlentities($id, ENT_QUOTES, 'utf-8');
        if(!isset($this->lang->$id)) {
            $message = $this->skin->error_box($this->lang->couldnt_find_message);
            $search = $this->skin->search($this->skin->no_results(), "", $message);
            return $search;
        }


        if($_POST['name']!="" && isset($_POST['value'])) {
            $name = htmlentities($_POST['name'], ENT_QUOTES, 'utf-8');
            if(!isset($this->lang->$name)) {
                $message = $this->skin->error_box($this->lang->couldnt_find_message);
            } else {
                $check_query = $this->db->execute("SELECT * FROM `lang` WHERE `name`=?", array($name));
                if ($old_message = $check_query->fetchrow()) {
                    $message_update_query['override'] = $_POST['value'];
                    $this->db->AutoExecute('lang', $message_update_query, 'UPDATE', '`id`='.intval($old_message['id']));
                } else {
                    $message_insert_query['name'] = $name;
                    $message_insert_query['override'] = $_POST['value'];
                    $this->db->AutoExecute('lang', $message_insert_query, 'INSERT');
                }
                $message = $this->skin->success_box($this->lang->message_saved);
            }
        }

        $single = $this->skin->single_message(htmlentities($id, ENT_QUOTES, 'utf-8'), htmlentities($this->lang->$id, ENT_QUOTES, 'utf-8'), $message);
        return $single;
    }

   /**
    * Deletes a message override
    *
    * @param int $id id of override to delete
    * @return string html
    */
    public function delete_override($id) {
        $override_query = $this->db->execute("SELECT * FROM `lang` WHERE `id`=?", array(intval($id)));

        if ($override_query->numrows() > 0) {
            $this->db->execute("DELETE FROM `lang` WHERE `id`=?", array($id));
            $delete_override = $this->search($this->skin->success_box($this->lang->override_deleted));
        } else {
            $delete_override = $this->search($this->skin->error_box($this->lang->no_such_override));
        }
        return $delete_override;
    }
}
?>
