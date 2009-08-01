<?php
/**
 * (TODO) Lotsa strings here : (
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

        $code_bank = $this->bank_switch();

        parent::construct($code_bank);
    }

   /**
    * decides where to go from here
    * (DONE) standardise "make_" function
    *
    * @param string $message error message
    * @return string html
    */
    public function make_bank($message = "") {
        $interest = intval($this->player->bank * 0.03);
        if ($this->player->interest) {
            $disabled = "disabled='disabled'";
            $tomorrow = "";
        } else {
            $tomorrow = $this->skin->lang_error->collect_interest_now;
            $disabled = "";
        }
        
        $make_bank = $this->skin->make_bank($this->player->gold, $this->player->bank, $interest, $disabled, $tomorrow, $message);
        return $make_bank;
    }

   /**
    * withdraw, deposit, interest?
    *
    * @return string html
    */
    public function bank_switch() {
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
            $deposit = $this->make_bank($this->skin->error_box($this->skin->lang_error->not_enough_cash_to_deposit));
            return $deposit;
        }
        $this->player->gold = $this->player->gold - abs(intval($_POST['amount']));
        $this->player->bank = $this->player->bank + abs(intval($_POST['amount']));

        $update_player['bank'] = $this->player->bank;
        $update_player['gold'] = $this->player->gold;
        $deposit_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $deposit_html = $this->make_bank($this->skin->deposited($this->player->gold, $this->player->bank));
        return $deposit_html;
    }

   /**
    * withdraw money
    *
    * @return string html
    */
    public function withdraw() {
        if (abs(intval($_POST['amount'])) > $this->player->bank) {
            $withdraw = $this->make_bank($this->skin->error_box($this->skin->lang_error->not_enough_cash_to_withdraw));
            return $withdraw;
        }
        $this->player->gold = $this->player->gold + abs(intval($_POST['amount']));
        $this->player->bank = $this->player->bank - abs(intval($_POST['amount']));

        $update_player['bank'] = $this->player->bank;
        $update_player['gold'] = $this->player->gold;
        $withdraw_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $withdraw = $this->make_bank($this->skin->withdrawn($this->player->gold, $this->player->bank));
        return $withdraw;
    }

    public function interest() {
        if ($this->player->interest) {
            $interest = $this->make_bank($this->skin->error_box($this->skin->lang_error->collect_interest_tomorrow));
            return $interest;
        }

        $interest_rate = intval($this->player->bank * 0.03);

        $this->player->gold = $this->player->gold + $interest_rate;
        $update_player['gold'] = $this->player->gold;
        $update_player['interest'] = 1;
        $interest_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);

        $interest = $this->make_bank($this->skin->interested($this->player->gold, $this->player->bank));
        return $interest;

    }

}
?>
