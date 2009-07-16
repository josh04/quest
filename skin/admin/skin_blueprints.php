<?php
/**
 * Admin items skin
 *
 * @author josh04
 * @package skin_admin
 */
class skin_blueprints extends _skin_admin {

    public function blueprints_wrap($blueprints_defense, $blueprints_offense, $blueprints_form) {
        $blueprints_wrap = $blueprints_form."<hr /><div>".$blueprints_defense."</div><div>".$blueprints_offense."</div>";
        return $blueprints_wrap;
    }

   /**
    * Does all the blueprint forms
    *
    * @param array $blueprint existing blueprint details
    * @param string $button what to display on the submit button
    * @param string $action which action to use in the url
    * @param string $remove_button display the remove button?
    * @param string $type_list list of available blueprint types
    * @param string $message error message
    * @return string html
    */
    public function blueprint_row($blueprint, $button, $action, $remove_button, $type_list, $message="") {
        $blueprint_row = $message."<form action='index.php?section=admin&amp;page=blueprints&amp;action=".$action."' method='POST'>
            <input type='hidden' name='blueprint_id' value='".$blueprint['id']."' />
            <table>
                <tr>
                    <td>Name:</td>
                    <td><input type='text' name='blueprint_name' value='".$blueprint['name']."' /></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><input type='text' name='blueprint_description' value='".$blueprint['description']."' /></td>
                </tr>
                <tr>
                    <td>Effectiveness:</td>
                    <td><input type='text' name='blueprint_effectiveness' value='".$blueprint['effectiveness']."' /></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input type='text' name='blueprint_price' value='".$blueprint['price']."' /></td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td>".$type_list."</td>
                </tr>
                <tr>
                    <td><input type='submit' name='blueprint_submit' value='".$button."' /></td>
                    ".$remove_button."
                </tr>
            </table></form>";
        return $blueprint_row;
    }

   /**
    * remove button!
    *
    * @return string html
    */
    public function remove_button() {
        $remove_button = "<td><input type='submit' name='blueprint_submit' value='".$this->lang_error->remove."' /></td>";
        return $remove_button;
    }

   /**
    * delete one?
    *
    * @param array $blueprint which to delete
    * @return string html
    */
    public function remove($blueprint) {
        $delete = "
                    Are you sure you want to delete ".$blueprint['name']."? It is currently being used by ".$blueprint['c']." players.<br /><br />
                    <form method='POST' action='index.php?section=admin&amp;page=blueprints&amp;action=remove_confirm'>
                        <input type='hidden' name='blueprint_id' value='".$blueprint['id']."' />
                        <input type='submit' value='Delete' />
                    </form>";
        return $delete;
    }

   /**
    * list of type choices
    *
    * @param int $current_type current type
    * @return string html
    */
    public function type_list($current_type) {
        $current_type = "type_".$current_type;
        $type_0 .= "<input type='radio' name='blueprint_type' value='0'";
        $type_1 .= "<input type='radio' name='blueprint_type' value='1'";
        $$current_type .= " checked='checked'";
        $type_0 .= " />Defense <br />";
        $type_1 .= " />Offense <br />";
        $type_list = $type_1.$type_0;
        return $type_list;
    }
}
?>
