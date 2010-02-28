<?php
/**
 * Addin' blueprints to the log
 * (TODO): images
 *
 * @author josh04
 * @package code_admin
 */
class code_blueprints extends code_common_admin {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_blueprints");

        $code_blueprints = $this->blueprints_switch();

        return $code_blueprints;
    }

   /**
    * which way, do we turn...
    *
    * @return string html
    */
    protected function blueprints_switch() {
        switch($_GET['action']) {
            case 'add':
                $blueprints_switch = $this->add();
                break;
            case 'modify':
                if ($_POST['blueprint_submit'] == "Remove") {
                    $blueprints_switch = $this->remove();
                } else {
                    $blueprints_switch = $this->modify();
                }
                break;
            case 'remove_confirm':
                $blueprints_switch = $this->remove_confirm();
                break;
            default:
                $blueprints_switch = $this->list_blueprints();
                break;
        }
        return $blueprints_switch;
    }

   /**
    * Show the ones already there
    *
    * @param string $message error message
    * @return string html
    */
    protected function list_blueprints($message="") {
        $blueprint_temp['name'] = htmlentities($_POST['blueprint_name'],ENT_QUOTES,'UTF-8');
        $blueprint_temp['description'] = htmlentities($_POST['blueprint_description'],ENT_QUOTES,'UTF-8');
        $blueprint_temp['effectiveness'] = abs(intval($_POST['blueprint_effectiveness']));
        $blueprint_temp['price'] = abs(intval($_POST['blueprint_price']));
        $blueprint_temp['type'] = intval($_POST['blueprint_type']);

        $blueprints_query = $this->db->execute("SELECT * FROM `blueprints` ORDER BY `name` ASC");

        if (!$blueprints_query->RecordCount()) {
            $list_blueprints = $this->skin->blueprint_row(array(), $this->lang->add, "add", $this->skin->remove_button(), $this->skin->type_list(0), $message.$this->skin->error_box($this->lang->no_blueprints));
            return $list_blueprints;
        }

        while ($blueprint = $blueprints_query->fetchrow()) {
            if ($blueprint->type == 1) {
                $blueprints_offense .= $this->skin->blueprint_row($blueprint, $this->lang->modify, "modify", $this->skin->remove_button(), $this->skin->type_list($blueprint['type']));
            } else {
                $blueprints_defense .= $this->skin->blueprint_row($blueprint, $this->lang->modify, "modify", $this->skin->remove_button(), $this->skin->type_list($blueprint['type']));
            }
        }
        $add_form = $this->skin->blueprint_row($blueprint_temp, $this->lang->add, "add", "", $this->skin->type_list(0), $message);
        $list_blueprints = $this->skin->blueprints_wrap($blueprints_offense, $blueprints_defense, $add_form);
        return $list_blueprints;
    }

   /**
    * they've added one!
    *
    * @return string html
    */
    protected function add() {
        $name = htmlentities($_POST['blueprint_name'],ENT_QUOTES,'UTF-8');
        $description = htmlentities($_POST['blueprint_description'],ENT_QUOTES,'UTF-8');
        $effectiveness = abs(intval($_POST['blueprint_effectiveness']));
        $price = abs(intval($_POST['blueprint_price']));
        $type = intval($_POST['blueprint_type']);

        if ($name == "") {
            $add = $this->list_blueprints($this->skin->error_box($this->lang->no_blueprint_name));
            return $add;
        }
        
        if ($description == "") {
            $add = $this->list_blueprints($this->skin->error_box($this->lang->no_blueprint_description));
            return $add;
        }



        $this->make_blueprint($name, $description, $effectiveness, $price, $type);
        $add = $this->list_blueprints($this->skin->success_box($this->lang->blueprint_added));
        return $add;
    }

   /**
    * just trying to expose a little functionality. Not sure who for, but still.
    *
    * @param string $name Name of item
    * @param string $description Description of item
    * @param int $effectiveness Strength of item
    * @param int $price Price of item
    * @param int $type 1 for Offense, 0 for Defense
    * @return int id of new item
    */
    public function make_blueprint($name, $description, $effectiveness, $price, $type) {
        $blueprint_insert['name'] = $name;
        $blueprint_insert['description'] = $description;
        $blueprint_insert['effectiveness'] = $effectiveness;
        $blueprint_insert['price'] = $price;
        if ($type) {
            $blueprint_insert['type'] = 1;
        } else {
            $blueprint_insert['type'] = 0;
        }

       $this->db->AutoExecute("blueprints", $blueprint_insert, "INSERT");
       return $this->db->Insert_Id();
    }

   /**
    * they've edited/deleted one!
    *
    * @return string html
    */
    protected function modify() {
        $blueprint_update['name'] = htmlentities($_POST['blueprint_name'],ENT_QUOTES,'UTF-8');
        $blueprint_update['description'] = htmlentities($_POST['blueprint_description'],ENT_QUOTES,'UTF-8');
        $blueprint_update['effectiveness'] = abs(intval($_POST['blueprint_effectiveness']));
        $blueprint_update['price'] = abs(intval($_POST['blueprint_price']));
        $blueprint_update['type'] = intval($_POST['blueprint_type']);
        
        if ($blueprint_update['type']) {
            $blueprint_update['type'] = 1;
        } else {
            $blueprint_update['type'] = 0;
        }
        
        if (!$_POST['blueprint_id']) {
            $modify = $this->list_blueprints($this->lang->no_blueprint_selected);
            return $modify;
        }
        
        if ($blueprint_update['name'] == "") {
            $modify = $this->list_blueprints($this->skin->error_box($this->lang->no_blueprint_name));
            return $modify;
        }
        
        if ($blueprint_update['description'] == "") {
            $modify = $this->list_blueprints($this->skin->error_box($this->lang->no_blueprint_description));
            return $modify;
        }

        $this->db->AutoExecute("blueprints", $blueprint_update, "UPDATE", "`id`=".intval($_POST['blueprint_id']));

        $modify = $this->list_blueprints($this->skin->success_box($this->lang->blueprint_modify));
        return $modify;
    }

   /**
    * they've definitely deleted one!
    *
    * @return string html
    */
    protected function remove() {
        if (!$_POST['blueprint_id']) {
            $remove = $this->list_blueprints($this->lang->no_blueprint_selected);
            return $remove;
        }

        $blueprint_query = $this->db->execute("SELECT `id`, `name` FROM `blueprints` WHERE `id`=? LIMIT 0,1", array(intval($_POST['blueprint_id'])));
        if (!$blueprint_query->RecordCount()) {
            $remove = $this->list_blueprints($this->lang->no_blueprint_selected);
            return $remove;
        }
        
        $blueprint = $blueprint_query->fetchrow();

        $items_query = $this->db->execute("SELECT COUNT(*) AS c FROM `items` WHERE `item_id`=?", array($blueprint['id']));
        $items_count = $items_query->fetchrow(); // nggggg
        $blueprint['c'] = $items_count['c'];

        $remove = $this->skin->remove($blueprint);
        return $remove;
    }

   /**
    * actually deletes one
    *
    * @return string html
    */
    protected function remove_confirm() {
        $remove_query = $this->db->execute('DELETE FROM `blueprints` WHERE `id`=?', array(intval($_POST['blueprint_id'])));
        
        if ($this->db->Affected_Rows()) {
            $message = $this->skin->success_box($this->lang->blueprint_removed);
        } else {
            $message = $this->skin->error_box($this->lang->blueprint_remove_failed);
        }
        $remove_confirm = $this->list_blueprints($message);
        return $remove_confirm;
    }
}
?>
