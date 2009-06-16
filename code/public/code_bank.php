<?php
/**
 * Description of code_bank
 *
 * @author josh04
 * @package code_public
 */
class code_bank extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_bank");

        $code_bank = $this->make_bank();

        parent::construct($code_bank);
    }

   /**
    * decides where to go from here
    * (TODO) standardise "make_" function
    *
    *
    */
    public function make_bank() {
        if ($_GET['action']) {
            $make_bank = $this->bank_actions();
            return $make_bank;
        }
        $interest = intval($this->player->bank * 0.03);
        $tomorrow = "You may collect your interest now.";
        $disabled = "";
        if ($this->player->interest) {
            $disabled = "disabled='disabled'";
            $tomorrow = "";
        }
        
        $make_bank = $this->skin->make_bank($this->player->gold, $this->player->bank, $interest, $disabled, $tomorrow);
        return $make_bank;
    }

   /**
    * withdraw, deposit, interest?
    *
    * @return string html
    */
    public function bank_actions() {
        switch ($_GET['action']) {
            case 'deposit':
                $bank_actions = $this->deposit();
                break;
            case 'withdraw':
                $bank_actions = $this->withdraw();
                break;
            case 'interest':
                $bank_actions = $this->interest();
                break;
            default:
                $_GET['action'] = false;
                $bank_actions = $this->make_bank();
                return $bank_actions;
        }
        return $bank_actions;
    }

   /**
    * deposit money
    *
    * @return string html
    */
    public function deposit() {
        if (abs(intval($_POST['amount'])) > $this->player->gold) {
            $deposit = "You do not have that much cash to deposit.";
            return $deposit;
        }
        $this->player->gold = $this->player->gold - abs(intval($_POST['amount']));
        $this->player->bank = $this->player->bank + abs(intval($_POST['amount']));

        $update_player['bank'] = $this->player->bank;
        $update_player['gold'] = $this->player->gold;
        $deposit_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $deposit = "You deposited your money into the bank. You now have £".$this->player->gold." on you and £".$this->player->bank." in the bank.";
        return $deposit;
    }

   /**
    * withdraw money
    *
    * @return string html
    */
    public function withdraw() {
        if (abs(intval($_POST['amount'])) > $this->player->bank) {
            $withdraw = "You do not have that much cash to withdraw.";
            return $withdraw;
        }
        $this->player->gold = $this->player->gold + abs(intval($_POST['amount']));
        $this->player->bank = $this->player->bank - abs(intval($_POST['amount']));

        $update_player['bank'] = $this->player->bank;
        $update_player['gold'] = $this->player->gold;
        $withdraw_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $withdraw = "You withdrawn your money from the bank. You now have £".$this->player->gold." on you and £".$this->player->bank." in the bank.";
        return $withdraw;
    }

    public function interest() {
        if ($this->player->interest) {
            $interest = "You can collect your interest again tomorrow.";
            return $interest;
        }

        $interest_rate = intval($this->player->bank * 0.03);

        $this->player->gold = $this->player->gold + $interest_rate;
        $update_player['gold'] = $this->player->gold;
        $update_player['interest'] = 1;
        $interest_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $interest = "You collected your interest. You now have £".$this->player->gold." on you and £".$this->player->bank." in the bank.";
        return $interest;

    }

}
?>
