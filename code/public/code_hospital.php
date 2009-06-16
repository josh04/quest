<?php
/**
 * hospital; heals shizz
 *
 * @author josh04
 * @package code_public
 */
class code_hospital extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_hospital");

        $code_hospital = $this->make_hospital();

        parent::construct($code_hospital);
    }

    public function make_hospital() {
        if ($this->player->hp == $this->player->hp_max) {
            $make_hospital = "You are already at full health.";
            return $make_hospital;
        }

        if ($_GET['action'] == 'heal') {
            $make_hospital = $this->heal();
            return $make_hospital;
        }
        $full_heal = $this->player->hp_max - $this->player->hp;
        $make_hospital = $this->skin->make_hospital($full_heal);
        return $make_hospital;
    }

    public function heal() {
        $heal = abs(intval($_POST['amount']));
        if ($_POST['full_heal'] == 'on') {
            $heal = $this->player->hp_max - $this->player->hp;
        }

        if ( $this->player->hp_max < $heal + $this->player->hp ){
            $heal_html = "That is beyond your maximum health.";
            return $heal_html;
        }

        if ($this->player->gold < $heal) {
            $heal_html = "You don't have enough money to heal that much.";
            return $heal_html;
        }

        $this->player->hp = $heal + $this->player->hp;
        $this->player->gold = $this->player->gold  - $heal;
        $update_player['hp'] = $this->player->hp;
        $update_player['gold'] = $this->player->gold;
        $heal_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id='.$this->player->id);

        $heal_html = "You have healed yourself by ".$heal." point(s). Your health is now ".$this->player->hp.".";
        return $heal_html;
    }

}
?>
