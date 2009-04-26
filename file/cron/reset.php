<?php

$config_server = "localhost"; //localhost
$config_database = "commande_jamiehwiki"; //teknotom_cherub
$config_username = "commande_questforum"; //Database username
$config_password = "cherub123"; //Database password
$secret_key = "carnetics"; //Secret key, make it a random word/sentence/whatever

//Do not edit below this line

include('/home/commande/public_html/quest/adodb/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Connect to database
$db->Connect($config_server, $config_username, $config_password, $config_database); //Select table

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
//$db->debug = true; //Debug

$query = $db->execute("update `players` set `energy`=`maxenergy`, `interest`=0, `hp`=`maxhp`");
?>