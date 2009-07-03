<?php

$onlinecountq = $this->db->execute("SELECT count(*) FROM players WHERE (last_active > (".(time()-(60*15))."))");
$onlinecount = $onlinecountq->fetchrow();
$moneyq = $this->db->execute("SELECT sum(`gold`) FROM players");
$money = $moneyq->fetchrow();
 
$header .="
<ul>
<li><span class='header'>General</span></li>
<li><a href='index.php'>Home</a></li>
<li><a href='index.php?page=login'>Register</a></li>
<li><a href='index.php?page=globalstats'>Player Stats</a></li>
<li><a href='index.php?page=guesthelp'>Help</a></li>
</ul>

<ul>
<li><span class='header'>Players Online</span></li><li><a>".$onlinecount['count(*)']."</a></li>
<li><span class='header'>Money in game</span></li><li><a>" . number_format($money['sum(`gold`)'],0,".",",") . "</a></li>
</ul>
"; 

?>
