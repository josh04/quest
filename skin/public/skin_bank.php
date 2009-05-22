<?php
/**
 * bank skin
 *
 * @author josh04
 * @package skin_public
 */
class skin_bank extends skin_common {

   /**
    * makes main bank page
    *
    * @param integer $gold
    * @param integer $bank
    * @param integer $interest
    * @param string $disabled
    * @param string $tomorrow
    * @return string html
    */
    public function make_bank($gold, $bank, $interest, $disabled, $tomorrow) {
        $bank_page = "  <b>Campus Assistant:</b><br />
                        <i>Welcome to the token bank, agent. What would you like to do?</i>
                        <br /><br />
                        <table width='100%'>
                        <tr>
                        <td width='50%'>
                        <fieldset>
                        <legend>Deposit tokens:</legend>
                        You have <b>".$gold."</b> tokens on you.<br />
                        <form method='post' action='index.php?page=bank&amp;action=deposit'>
                        <input type='text' name='amount' value='".$gold."' />
                        <input type='submit' value='Deposit'/>
                        </form>
                        </fieldset>
                        </td>
                        <td rowspan='2' width='50%'>
                        <fieldset class='empty'>
                        <legend>Collect Interest</legend>
                        The interest rate is at: 3%
                        <br /><br />
                        <form method='post' action='index.php?page=bank&amp;action=interest'>
                        <input type='submit' value='Collect' ".$disabled." />
                        </form>
                        <br />".$tomorrow."<br />
                        Your daily interest is: <b>".$interest."</b> token points
                        </fieldset>
                        </td>
                        </tr>
                        <tr>
                        <td width='50%'>
                        <fieldset>
                        <legend>Withdraw tokens:</legend>
                        You have <b>".$bank."</b> tokens in your bank account.<br />
                        <form method='post' action='index.php?page=bank&amp;action=withdraw'>
                        <input type='text' name='amount' value='".$bank."' />
                        <input type='submit' value='Withdraw'/>
                        </form>
                        </fieldset>
                        </td>
                        </tr>
                        </table>";
        return $bank_page;
    }
}
?>
