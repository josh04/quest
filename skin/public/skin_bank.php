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
    * @param integer $gold amount in hand
    * @param integer $bank_gold_deposited amount pending
    * @param integer $bank_gold_saved amount in bank
    * @param integer $interest amount of interest
    * @param string $disabled disables the interest box
    * @param string $next_interest how much will they gain?
    * @param string $message error message
    * @return string html
    */
    public function make_bank($gold, $bank_gold_deposited, $bank_gold_saved, $interest, $disabled, $next_interest, $message="") {
        $bank_page = $message."  <h2>Bank</h2>
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
                        The interest rate is at: 3%.<br />
                        Your next interest payment will be £".$next_interest.".

                        <form method='post' action='index.php?page=bank&amp;action=interest'><br />
                        <input type='submit' value='Collect' ".$disabled." />
                        </form>
                        <br />
                        Your current compound interest is: <b>£".$interest."</b>.

                        </td>
                        </tr>

                        <tr>
                        <td style='width:50%;border:1px solid #CCC;padding:4px;'>
                        <strong>Withdraw tokens:</strong><br />
                        You have <strong>".$bank_gold_saved."</strong> tokens in your bank account.<br />
                        You have <strong>".$bank_gold_deposited."</strong> tokens pending. They will be deposited in your account after your next
                        interest payment.<br />
                        <form method='post' action='index.php?page=bank&amp;action=withdraw'>
                        <input type='text' name='amount' value='".$bank."' />
                        <input type='submit' value='Withdraw'/>
                        </form>
                        </td>
                        </tr>
                        </table>";
        return $bank_page;
    }

   /**
    * message for depositing money
    *
    * @param int $gold amount of carried gold
    * @param int $bank amount of bank gold
    * @return string html
    */
    public function deposited($gold, $bank) {
        $deposited = "<div class='success'>You deposited your money into the bank. You now have £".$gold." on you and £".$bank." pending.</div>";
        return $deposited;
    }

   /**
    * message for withdrawing money
    *
    * @param int $gold amount of carried gold
    * @param int $bank amount of bank gold
    * @return string html
    */
    public function withdrawn($gold, $bank) {
        $withdrawn = "<div class='success'>You withdrawn your money from the bank. You now have £".$gold." on you and £".$bank." in the bank.</div>";
        return $withdrawn;
    }

   /**
    * message for collecting interest
    *
    * @param int $gold amount of carried gold
    * @return string html
    */
    public function interested($gold) {
         $interested = "<div class='success'>You collected your interest. You now have £".$gold." on you.</div>";
         return $interested;
   }
}
?>