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

        $bank_query = $this->db->execute("SELECT * FROM `bank` WHERE `player_id`=?", array($this->player->id));

        if (!$account = $bank_query->fetchrow()) {
            $account['player_id'] = $this->player->id;
            if (IS_UPGRADE) {
                $account['bank_gold_saved'] = $this->player->bank;
            } else {
                $account['bank_gold_saved'] = 0;
            }
            $account['bank_gold_deposited'] = abs(intval($_POST['amount']));
            $account['interest_owed'] = 0;
            $account['last_deposited'] = time();

            $this->db->AutoExecute('bank', $account, 'INSERT');
        }

        if (!$account['interest_owed']) {
            $disabled = "disabled='disabled'";
        } else {
            $disabled = "";
        }
        
        $next_interest = round($account['bank_gold_saved']*0.3);

        $make_bank = $this->skin->make_bank($this->player->gold, $account['bank_gold_deposited'], $account['bank_gold_saved'],
            intval($account['interest_owed']), $disabled, $next_interest, $message);
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

        $bank_query = $this->db->execute("SELECT * FROM `bank` WHERE `player_id`=?", array($this->player->id));

        if (!$account = $bank_query->fetchrow()) {
            $deposit = $this->make_bank($this->skin->error_box($this->lang->not_enough_cash_to_deposit));
            return $deposit;
        }

        if (abs(intval($_POST['amount'])) > $this->player->gold) {
            $deposit = $this->make_bank($this->skin->error_box($this->lang->not_enough_cash_to_deposit));
            return $deposit;
        }

        $this->player->gold = $this->player->gold - abs(intval($_POST['amount']));

        $player_update_query['gold'] = $this->player->gold;
        $this->db->AutoExecute('players', $player_update_query, 'UPDATE', '`id`='.$this->player->id);

        $bank_update_query['bank_gold_deposited'] = $account['bank_gold_deposited'] + abs(intval($_POST['amount']));
        $this->db->AutoExecute('bank', $bank_update_query, 'UPDATE', '`player_id`='.$this->player->id);


        $deposit_html = $this->make_bank($this->skin->deposited($this->player->gold, $bank_update_query['bank_gold_deposited']));
        return $deposit_html;
    }

   /**
    * withdraw money
    *
    * @return string html
    */
    public function withdraw() {

        $bank_query = $this->db->execute("SELECT * FROM `bank` WHERE `player_id`=?", array($this->player->id));

        if (!$account = $bank_query->fetchrow()) {
            $withdraw = $this->make_bank($this->skin->error_box($this->lang->not_enough_cash_to_withdraw));
            return $withdraw;
        }

        if (abs(intval($_POST['amount'])) > $account['bank_gold_saved']) {
            $withdraw = $this->make_bank($this->skin->error_box($this->lang->not_enough_cash_to_withdraw));
            return $withdraw;
        }


        $this->player->gold = $this->player->gold + abs(intval($_POST['amount']));

        $player_update_query['gold'] = $this->player->gold;
        $this->db->AutoExecute('players', $player_update_query, 'UPDATE', '`id`='.$this->player->id);


        $bank_update_query['bank_gold_saved'] = $account['bank_gold_saved'] - abs(intval($_POST['amount']));
        $this->db->AutoExecute('bank', $bank_update_query, 'UPDATE', '`player_id`='.$this->player->id);

        $withdraw = $this->make_bank($this->skin->withdrawn($this->player->gold, $bank_update_query['bank_gold_saved']));
        return $withdraw;
    }

   /**
    * gives player interest
    *
    * @return string html
    */
    public function interest() {

        $bank_query = $this->db->execute("SELECT * FROM `bank` WHERE `player_id`=?", array($this->player->id));

        if (!$account = $bank_query->fetchrow()) {
            $interest = $this->make_bank($this->skin->error_box($this->lang->collect_interest_tomorrow));
            return $interest;
        }

        if (!$account['interest_owed']) {
            $interest = $this->make_bank($this->skin->error_box($this->lang->collect_interest_tomorrow));
            return $interest;
        }


        $this->player->gold = $this->player->gold + round($account['interest_owed']);
        $update_player['gold'] = $this->player->gold;
        $interest_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', '`id` = '.$this->player->id);

        $bank_update_query['interest_owed'] = 0;

        $this->db->AutoExecute('bank', $bank_update_query, 'UPDATE', '`player_id`='.$this->player->id);

        $interest = $this->make_bank($this->skin->interested($this->player->gold));
        return $interest;

    }

}
?>
