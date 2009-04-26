<?php
  //function shop_page($items)
  $items = $a[0];
  			 while ($item = $items->fetchrow()) {
			   if ($item['type'] == 0) {
				  $defense .= "<fieldset>\n
            <legend><b>" . $item['name'] . "</b></legend>\n
            <table width=\"100%\">\n
            <tr><td width=\"85%\">
            ".$item['description'] . "\n<br />
            <b>Effectiveness:</b> " . $item['effectiveness'] . "
            </td><td width=\"15%\">
            <b>Price:</b> " . $item['price'] . "<br />
            <a href=\"index.php?page=shop&amp;action=buy&amp;id=" . $item['id'] . "\">Buy</a><br />
            </td></tr>
            </table></fieldset>\n<br />";
          } else {
				    $offense .= "<fieldset>\n
            <legend><b>" . $item['name'] . "</b></legend>\n
            <table width=\"100%\">\n
            <tr><td width=\"85%\">
            ".$item['description'] . "\n<br />
            <b>Effectiveness:</b> " . $item['effectiveness'] . "
            </td><td width=\"15%\">
            <b>Price:</b> " . $item['price'] . "<br />
            <a href=\"index.php?page=shop&amp;action=buy&amp;id=" . $item['id'] . "\">Buy</a><br />
            </td></tr>
            </table></fieldset>\n<br />";
          }
			  }
			 $this->html .= "Defensive Items:<br /><div>".$defense."</div>";
			 $this->html .= "Offensive Items:<br /><div>".$offense."</div>";
			 $this->final_output();

?>
