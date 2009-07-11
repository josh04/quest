<?php
/**
 * Admin items skin
 *
 * @author josh04
 * @package skin_admin
 */
class skin_blueprints extends skin_common {

    public function blueprints_wrap($blueprints_defense, $blueprints_offense, $blueprints_form) {
        $blueprints_wrap = $blueprints_form."<div>".$blueprints_defense."</div><div>".$blueprints_offense."</div>";
        return $blueprints_wrap;
    }

   /**
    * Does all the blueprint forms
    *
    * @param array $blueprint existing blueprint details
    * @param string $button what to display on the submit button
    * @param string $action which action to use in the url
    * @param string $message error message
    * @return string html
    */
    public function blueprint_row($blueprint, $button, $action, $message="") {
        $blueprint_row = $message."<form action='index.php?section=admin&amp;page=blueprints&amp;action=".$action."' method='POST'>
            <input type='hidden' name='id' value='".$blueprint['id']."' />
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
                    <td><input type='radio' name='type' value='Offense' />
                        <input type='radio' name='type' value='Defense' checked='checked' /></td>
                </tr>
                <tr><td><input type='submit' value='".$button."' /></td></tr>
            </table></form>";
        return $blueprint_row;
    }

}
?>
