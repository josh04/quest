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
        $make_hospital = $message."
            <h2>Hospital</h2>
            <div><b>Nurse:</b><br />
            <i>You have been in the wars, haven't you?<br />
            Well, to completely heal yourself will cost <b>".$full_heal."</b> tokens.<br />
            Otherwise, you can partially heal yourself by entering the amount of hp you want to regain below.</i></div>

            <div>
            <form action='index.php?page=hospital&amp;action=heal' method='post'>
            Amount to heal: <input type='text' ".$disabled." name='amount' /><br />
            <label for='full_heal'>Full heal: </label><input type='checkbox' ".$disabled." name='full_heal' /><br />
            <input type='submit' ".$disabled." value='Heal'/>
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
        $healed = "You have healed yourself by ".$heal." point(s). Your health is now ".$hp.".";
        return $healed;
    }

}
?>
