<?php
/**
 * hospital; heals shizz
 *
 * @author josh04
 * @package code_public
 */
class code_hospital extends code_common {

    public $player_class = "code_player_rpg";

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_hospital");

        $code_hospital = $this->hospital_switch();

        parent::construct($code_hospital);
    }

   /**
    * not many options here.
    *
    * @return string html
    */
    protected function hospital_switch() {
        if ($_GET['action'] == 'heal') {
            $make_hospital = $this->heal();
            return $make_hospital;
        }

        $make_hospital = $this->make_hospital();
        return $make_hospital;
    }

   /**
    * makes the hospital. how generous.
    *
    * @param string $message error time
    * @return string html
    */
    protected function make_hospital($message = "") {
        if ($this->player->hp == $this->player->hp_max) {
            $message .= $this->skin->error_box($this->lang->full_health);
            $disabled = "disabled='disabled'";
        }

        $full_heal = $this->player->hp_max - $this->player->hp;
        $make_hospital = $this->skin->make_hospital($full_heal, $disabled, $message);
        return $make_hospital;
    }

    protected function heal() {
        if ($this->player->hp == $this->player->hp_max) {
            $heal = $this->make_hospital();
            return $heal;
        }

        if ($_POST['full_heal'] == 'on') {
            $_POST['amount'] = $this->player->hp_max - $this->player->hp;
        }

        if (!$_POST['amount']) {
            $heal = $this->make_hospital($this->skin->error_box($this->lang->no_amount_to_heal_entered));
            return $heal;
        }

        $heal = abs(intval($_POST['amount']));
        


        if ( $this->player->hp_max < $heal + $this->player->hp ){
            $heal_html = $this->make_hospital($this->skin->error_box($this->lang->beyond_maximum));
            return $heal_html;
        }

        if ($this->player->gold < $heal) {
            $heal_html = $this->make_hospital($this->skin->error_box($this->lang->not_enough_to_heal));
            return $heal_html;
        }

        $this->player->hp = $heal + $this->player->hp;
        $this->player->gold = $this->player->gold  - $heal;
        $update_player['hp'] = $this->player->hp;
        $update_player['gold'] = $this->player->gold;
        $heal_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id='.$this->player->id);

        $heal_html = $this->make_hospital($this->skin->healed($heal, $this->player->hp));
        return $heal_html;
    }

}
?>
