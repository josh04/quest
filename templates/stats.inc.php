<?php
  //function stats_page() { 
  //Stats page. There are two outputs, depending on
  //whether they have any stat points or not.
    if (intval($this->player->stat_points) != 0) {
        $this->html = "<b>Stat Training Centre:</b><br />
                       <i>You have ".$this->player->stat_points." 
                       stat points to spend, agent. What would you 
                       like to spend them on?</i>
                       <br /><br />
                       <a href='index.php?page=stats&amp;spend=1'>Strength</a><br />
                       <a href='index.php?page=stats&amp;spend=2'>Vitality</a><br />
                       <a href='index.php?page=stats&amp;spend=3'>Agility</a><br />";
    } else {
        $this->html = "<b>Stat Training Centre:</b><br />
                       <i>Sorry agent, but you currently do not have any 
                       stat points to spend.<br />
                       Please come back later when you have leveled up.</i>";
    }
    $this->final_output();

?>
