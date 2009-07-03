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
        $bank_page = "  <h2>Bank</h2>
                        <strong>Campus assistant:</strong>
                        <div style='font-style:italic;'>Welcome to the token bank, agent. What would you like to do?</div>
                        <br />
                        <table style='width:100%;' cellspacing='4'>
                        <tr>
                        <td style='width:50%;border:1px solid #CCC;padding:4px;'>
                        <strong>Deposit tokens:</strong><br />
                        You have <b>".$gold."</b> tokens on you.<br />
                        <form method='post' action='index.php?page=bank&amp;action=deposit'>
                        <input type='text' name='amount' value='".$gold."' />
                        <input type='submit' value='Deposit'/>
                        </form>
                        </td>

                        <td rowspan='2'  style='width:50%;border:1px solid #CCC;padding:4px;'>
                        <strong>Collect Interest</strong><br />
                        The interest rate is at: 3%

                        <form method='post' action='index.php?page=bank&amp;action=interest'>
                        <input type='submit' value='Collect' ".$disabled." />
                        </form>
                        <br />".$tomorrow."<br />
                        Your daily interest is: <b>".$interest."</b> token points
                        </td>
                        </tr>

                        <tr>
                        <td style='width:50%;border:1px solid #CCC;padding:4px;'>
                        <strong>Withdraw tokens:</strong><br />
                        You have <b>".$bank."</b> tokens in your bank account.<br />
                        <form method='post' action='index.php?page=bank&amp;action=withdraw'>
                        <input type='text' name='amount' value='".$bank."' />
                        <input type='submit' value='Withdraw'/>
                        </form>
                        </td>
                        </tr>
                        </table>";
        return $bank_page;
    }
}
?>