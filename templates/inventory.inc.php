<?php

  //Creates the inventory page. I'd hoped to do it with the same code as the laptop,
  //but it proved impossible/too complex.
  //function inventory_page($items)
  $items = $a[0];
  
 	 while($item = $items->fetchrow()) {
	 	$status = ($item['status'] == 1) ? "Unequip":"Equip";
	  if ($item['type'] == 1) {
	    $weapons .= "
		  <fieldset><legend>
		  <b>".$item['name']."</b></legend>
		  <table width='100%'>
		  <tr><td width='85%''>
		  ".$item['description']."<br />
		  <b>Effectiveness:</b> ".$item['effectiveness']."
		  </td><td width='15%'>
		  <a href='index.php?page=shop&amp;action=sell&amp;id=".$item['id']."'>Sell</a><br />
		  <a href='index.php?page=inventory&amp;action=equip&amp;id=".$item['id']."'>".$status."</a>
		  </td></tr>
		  </table>
		  </fieldset><br />";
	 } else {
	    $armour .= "
		  <fieldset><legend>
		  <b>".$item['name']."</b></legend>
		  <table width='100%'>\n
		  <tr><td width='85%'>
		  ".$item['description']."<br />
		  <b>Effectiveness:</b> ".$item['effectiveness']."
		  </td><td width='15%'>
		  <a href='index.php?page=shop&amp;action=sell&amp;id=".$item['id']."'>Sell</a><br />
		  <a href='index.php?page=inventory&amp;action=equip&amp;id=".$item['id']."'>".$status."</a>
		  </td></tr>
	 	  </table>
	 	  </fieldset><br />";
    }
	 }
   $this->html .= "<b>Weapons:</b><br />";
	 $this->html .= $weapons;	 
   $this->html .="<b>Armour:</b><br />";
   $this->html .= $armour;
	 $this->final_output();
?>
