<?php
require('adodb5/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Get our database object.

$db->Connect('localhost',mainsit','-i,SUi->f?Wd','mainsite'; //Select table

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
// ------------------------
		$sent = $db->execute("INSERT INTO site (`body`, `title`, `linked`, `name`) 
                          VALUES (?,?,?,?)", 
                          array("Main page content",
                                "title of page",
                                1,
                                "test"));
?>