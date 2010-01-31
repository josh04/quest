<?php
/**
 * Player training
 *
 * @author josh04
 * @package code_public
 */
class code_work extends code_common {

    public $player_flags = array("rpg");

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_work");

        $code_work = $this->work_switch();

        return $code_work;
    }

    public function work_switch() {
        if ($this->player->energy == 0) {
            $make_work = $this->make_work($this->skin->error_box($this->lang->no_energy_to_work));
            return $make_work;
        }

        if ($_POST['action'] == "Work") {
            $work_switch = $this->work();
            return $work_switch;
        }

        $work_switch = $this->make_work();
        return $work_switch;
    }

   /**
    * handles all work
    *
    * @param string $message error message
    * @return string html
    */
    public function make_work($message="") {
        $make_work = $this->skin->make_work($message);
        return $make_work;
    }

   /**
    * they did it
    *
    * @return string html
    */
    public function work() {
        $this->player->energy--;
        $this->player->gold = $this->player->gold + 50 * $this->player->level;
        $this->player->update_player();
        $make_work = $this->make_work($this->skin->worked($this->player->gold));
        return $make_work;
    }

}
?>
