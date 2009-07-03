<?php
// admin/index.php : main admin thing!
// Let's get this party started.

session_start(); // Starts session.
require("core/config.php"); // Get config values.

// Set up the Database
require('adodb/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Get our database object.

$db->Connect($config['server'], 
             $config['db_username'], 
             $config['db_password'], 
             $config['database']); //Select table

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
// ------------------------

require("core/functions.php"); //Get universal functions.
require("core/player.php");    //Get our player class.

// Get our player object
$player = new player;
$player->db =& $db; //Database is passed to player object.
//Is our player a member, or a guest?
$player->check_user($config['secret_key']) ? $player->is_member = 1 : $player->is_member = 0;

// Get our display:
require("core/display.php");
$display = new display;
$display->ext .= "admin/";
$display->player =& $player; //This allows display to edit our player.
$display->db =& $db;         //and use our database.


//If we aren't verfied or enabled, we print a simple message and flee.
// Had to cut this. mail() is dangerously unsafe and as of yet I have no alternative.
//if ($player->verified == "0") {
//  $display->simple_page("You need to verify your account!");
//  exit;
//} else
if ( $player->rank != 'admin' || $player->is_member == 0) {
  $display->simple_page("You lack the secure file access necessary to view this page","","bred");
  exit;
}

//We're going to include files based on their
//filename compared to $_GET['page']. However,
//we don't want them to be able to include
//whatever file they want. So, we have an array
//which 'translates' them so to speak. If it 
//isn't in the array, it's not included.

$pages = array( "tasks"         =>          "tasks",
                "ticket"        =>          "ticket",
                "phpmy"         =>          "phpmy"         ); 
                
if($pages[$_GET['page']]) {
  require("admin/".$pages[$_GET['page']].".admin.php"); //Include whichever php file we want.
} else {
  $display->html_page("
<h4>Admin Home</h4>
<div class='bblue'>Welcome to the admin home. Grab a cup of tea, relax and maybe listen to some music. We don't do work here, just mess about with members' accounts and stuff like that. We're not mean, just fun-loving.</div>
<strong>Coming soon:</strong>
<ul style='list-style-image: url(images/icons/user_red.png);'>
<li>Edit members' accounts</li>
<li>Add and Change news on front page</li>
<li>Edit the help system (Please don't play with this when it's in beta. PLEASE!)</li>
<li>Read other people's PMs</li>
<li>Leave notes for other admins and take part in conversation about various admin-y topics.</li>
<li>Eat cake (You guys think we should let them do this?)</li>
</ul>
");                     //If it ain't there, GOTO index.
}


?>