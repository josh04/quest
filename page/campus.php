<?php
// campus.php : Campus Menu
// Just a menu. Does nothing. 

$display->html_page("
<strong>Security Guard:</strong><br />
<i>Welcome onto Campus, agent. Let me just check your ID.<br />
...<br />
You have permission to enter, ".$player->username.". If you follow the path to the left, you can get to the Main Building. To the right you'll find the training areas and accommodation. Have a good day.</i>

<div class=\"bgreen\" style=\"font-size:16px;\">
<table style=\"width:100%;\"><tr>

<td style=\"width:50%;\"><strong>Main Building</strong><br />
<a href=\"index.php?page=hospital\">Hospital</a><br />
<a href=\"index.php?page=bank\">Bank</a><br />
<a href=\"index.php?page=shop\">Store</a><br />
<a href=\"index.php?page=work\">Work</a>
</td>

<td style=\"width:50%;\"><strong>Your Room</strong><br />
<a href=\"index.php?page=laptop\">Laptop [".unread_log($player->id, $db)."]</a><br />
<a href=\"index.php?page=inventory\">Inventory</a><br /><br />

<strong>Dojo</strong><br />
<a href=\"index.php?page=battle_search\">Combat Training</a>
</td>

</tr></table></div>
");

?>