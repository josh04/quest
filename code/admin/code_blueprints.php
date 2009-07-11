<?php
/**
 * Addin' blueprints to the log
 * (TODO): images
 *
 * @author josh04
 * @package code_admin
 */
class code_blueprints extends _code_admin {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_blueprints");

        $code_blueprints = $this->blueprints_switch();

        parent::construct($code_blueprints);
    }

   /**
    * which way, do we turn...
    *
    * @return string html
    */
    public function blueprints_switch() {
        if ($_GET['action'] == "add") {
            $blueprints_switch = $this->add();
            return $blueprints_switch;
        }

        if ($_GET['action'] == "remove") {
            $blueprints_switch = $this->remove();
            return $blueprints_switch;
        }

        $blueprints_switch = $this->list_blueprints();
        return $blueprints_switch;
    }

   /**
    * Show the ones already there
    *
    * @return string html
    */
    public function list_blueprints() {
        $blueprints_query = $this->db->execute("SELECT * FROM `blueprints` ORDER BY `name` ASC");

        if (!$blueprints_query->RecordCount()) {
            $list_blueprints = $this->skin->blueprint_row(array(), $this->skin->lang_error->add, "add", $this->skin->lang_error->no_blueprints);
            return $list_blueprints;
        }

        while ($blueprint = $blueprints_query->fetchrow()) {
            if ($blueprint->type == 1) {
                $blueprints_offense = $this->skin->blueprint_row($blueprint, $this->skin->lang_error->modify, "modify");
            } else {
                $blueprints_defense = $this->skin->blueprint_row($blueprint, $this->skin->lang_error->modify, "modify");
            }
        }

        $list_blueprints = $this->skin->blueprints_wrap($blueprints_offense, $blueprints_defense, $this->skin->blueprints_form());
        return $list_blueprints;
    }

   /**
    * they've added one!
    *
    * @return string html
    */
    public function add() {
        
    }

   /**
    * they've removed one!
    *
    * @return string html
    *
    */
    public function remove() {

    }
}
?>
