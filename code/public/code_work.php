<?php
/**
 * Player training
 *
 * @author josh04
 * @package code_public
 */
class code_work extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct_page() {
        $this->initiate("skin_work");

        $code_work = $this->make_work();

        parent::construct_page($code_work);
    }

   /**
    * handles all work
    *
    * @return string html
    */
    public function make_work() {
        if ($this->player->energy == 0) {
            $make_work ="You have no energy left! You must rest a while.";
            return $make_work;
        }

        if ($_POST['action'] == "Work") {
            $this->player->energy--;
            $this->player->gold = $this->player->gold + 50 * $this->player->level;
            $update_player['energy'] = $this->player->energy;
            $update_player['gold'] = $this->player->gold;
            $work_query = $this->db->AutoExecute('players', $update_player, 'UPDATE', 'id = '.$this->player->id);
            $make_work = "After a long and tiring day, you have now finished working and now have £".$this->player->gold.".";
            return $make_work;
        }
        $make_work = $this->skin->make_work();
        return $make_work;
    }

}
?>
