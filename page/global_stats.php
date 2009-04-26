<?php
// campus.php : Campus Menu
// Just a menu. Does nothing. 

$richestq = $db->execute("SELECT * FROM `players` ORDER BY `gold` DESC LIMIT 10");
while($richest = $richestq->fetchrow()) {
$richtext .= "<tr><td><a href=\"index.php?page=profile&id=".$richest['id']."\">".$richest['username']."</a></td>
<td>".$richest['gold']."</td></tr>";
}

$killsq = $db->execute("SELECT * FROM `players` ORDER BY `kills` DESC LIMIT 10");
while($richest = $killsq->fetchrow()) {
$killtext .= "<tr><td><a href=\"index.php?page=profile&id=".$richest['id']."\">".$richest['username']."</a></td>
<td>".$richest['kills']." kills</td></tr>";
}

$deathsq = $db->execute("SELECT * FROM `players` ORDER BY `deaths` DESC LIMIT 10");
while($richest = $deathsq->fetchrow()) {
$deathtext .= "<tr><td><a href=\"index.php?page=profile&id=".$richest['id']."\">".$richest['username']."</a></td>
<td>".$richest['deaths']." deaths</td></tr>";
}

$display->html_page("
<strong>Notice Board:</strong><br />
<i>This notice board contains statistics of all the agents on Campus. See who's performing the best and who's not doing so well and see if you can feature on the top rankings!</i>

<table style=\"width:95%;\"><tr><td style=\"width:50%;\"><div class=\"bblue\" style=\"width:95%;\">
<strong>Assassins</strong>
<table>
".$killtext."
</table>
</div>

<div class=\"bblue\" style=\"width:95%;\">
<strong>Quitters</strong>
<table>
".$deathtext."
</table>
</div>
</td>

<td style=\"width:50%;\"><div class=\"bblue\" style=\"width:95%;\">
<strong>Top 10 Richest</strong>
<table>
".$richtext."
</table>
</div>

<div class=\"byellow\" style=\"width:95%;\">
Got some stats you'd like to see? Think it would be nice to have a list of those with the highest experience? Send us a <a href=\"index.php?page=ticket\">ticket</a> and tell us what we're missing.
</div>

</td></tr></table>
");

?>