<?php
// function bank()

    ($this->player->interest) ? $disabled="disabled='disabled'" : $tomorrow = "You may collect your interest now.";
    $interest = intval($this->player->bank * 0.03);
$this->html = "<b>Campus Assistant:</b><br />
    <i>Welcome to the token bank, agent. What would you like to do?</i>
<br /><br />
<table width='100%'>
<tr>
<td width='50%'>
<fieldset>
<legend>Deposit tokens:</legend>
You have <b>".$this->player->gold."</b> tokens on you.<br />
<form method='post' action='index.php?page=bank&amp;action=deposit'>
<input type='text' name='amount' value='".$this->player->gold."' />
<input type='submit' value='Deposit'/>
</form>
</fieldset>
</td>
<td rowspan='2' width='50%'>
<fieldset class='empty'>
<legend>Collect Interest</legend>
The interest rate is at: 3%
<br /><br />
<form method='post' action='index.php?page=bank&amp;action=interest'>
<input type='submit' value='Collect' ".$disabled." />
</form>
<br />".$tomorrow."<br />
Your daily interest is: <b>".$interest."</b> token points
</fieldset>
</td>
</tr>
<tr>
<td width='50%'>
<fieldset>
<legend>Withdraw tokens:</legend>
You have <b>".$this->player->bank."</b> tokens in your bank account.<br />
<form method='post' action='index.php?page=bank&amp;action=withdraw'>
<input type='text' name='amount' value='".$this->player->bank."' />
<input type='submit' value='Withdraw'/>
</form>
</fieldset>
</td>
</tr>
</table>";
    $this->final_output();
?>
