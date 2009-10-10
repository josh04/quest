<?php
/**
 * Description of skin_hospital
 *
 * @author josh04
 * @package skin_public
 */
class skin_hospital extends skin_common {

   /**
    * makes the hospital page
    *
    * @param int $full_heal max heal amount
    * @param string $message error time
    * @return string html
    */
    public function make_hospital($full_heal, $disabled, $message) {
        $make_hospital = $message."<br />
            <h2>Hospital</h2>
            <div>
            <br />
            <form action='index.php?page=hospital&amp;action=heal' method='post'>
            <table>
            <tr><td style='width:30%;'><label for='heal_amount'>Select amount to heal:</label></td>
            <td><input type='text' ".$disabled." name='amount' id='heal_amount' /></td></tr>

            <tr><td><label for='full_heal' style='display:inline;'>Full heal:</label></td>
            <td><input type='checkbox' ".$disabled." name='full_heal' id='full_heal' /></td></tr>

            <tr><td colspan='2'><input type='submit' ".$disabled." value='Heal'/></td></tr>
            </table>
            </form></div>";
        return $make_hospital;

    }

   /**
    * message to be displayed upon healing.
    *
    * @param int $heal amount healed
    * @param int $hp amount total
    * @return string html
    */
    public function healed($heal, $hp) {
        $healed = "<div class='success'>You have healed yourself by ".$heal." point(s). Your health is now ".$hp.".</div>";
        return $healed;
    }

}
?>
