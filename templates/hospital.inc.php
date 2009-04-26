<?php
  //function hospital_page()
    $heal = $this->player->maxhp - $this->player->hp;
$this->html .= "
<div style=\"margin:6px;\"><b>Nurse:</b><br />
<i>You have been in the wars, haven't you?<br />
Well, to completely heal yourself will cost <b>".$heal."</b> tokens.<br />
Otherwise, you can partially heal yourself by entering the amount of hp you want to regain below.</i></div>

<a href='index.php?page=hospital&amp;action=fullheal'>Heal fully!</a><br />

<div class=\"bblue\">
<form action='index.php?page=hospital&amp;action=heal' method='post'>
Amount to heal: <input type='text' name='amount' />
<input type='submit' value='Heal'/>
</form></div>";
    $this->final_output();
?>
