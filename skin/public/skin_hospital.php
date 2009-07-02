<?php
/**
 * Description of skin_hospital
 *
 * @author josh04
 * @package skin_public
 */
class skin_hospital extends skin_common {

    public function make_hospital($full_heal) {

        $make_hospital = "
            <h2>Hospital</h2>
            <div><b>Nurse:</b><br />
            <i>You have been in the wars, haven't you?<br />
            Well, to completely heal yourself will cost <b>".$full_heal."</b> tokens.<br />
            Otherwise, you can partially heal yourself by entering the amount of hp you want to regain below.</i></div>

            <div>
            <form action='index.php?page=hospital&amp;action=heal' method='post'>
            Amount to heal: <input type='text' name='amount' /><br />
            <label for='full_heal'>Full heal: </label><input type='checkbox' name='full_heal' /><br />
            <input type='submit' value='Heal'/>
            </form></div>";
        return $make_hospital;

    }

}
?>
