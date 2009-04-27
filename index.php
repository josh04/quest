<?php
/**
 * index.php
 *
 * main bootstrap
 * @package code_common
 * @author josh04
 */


session_start(); // Starts session.

// ------------------------

require('adodb/adodb.inc.php'); //Include adodb files
//require("config/functions.php"); //Get universal functions.
require("code/common/code_player.php");    //Get our player class.
require("code/common/code_common.php");
require("skin/common/skin_common.php");

//We're going to include files based on their
//filename compared to $_GET['page']. However,
//we don't want them to be able to include
//whatever file they want. So, we have an array
//which 'translates' them so to speak. If it 
//isn't in the array, it's not included.

$pages = array( "login"         =>          "login",
                "stats"         =>          "stats",
                "laptop"        =>          "log",
                "inventory"     =>          "inventory",
                "bank"          =>          "bank",
                "hospital"      =>          "hospital",
                "work"          =>          "work",
                "help"          =>          "help",
                "editprofile"   =>          "edit_profile",
                "battle_search" =>          "battle_search",
                "battle"        =>          "battle",
                "profile"       =>          "profile",
                "ticket"        =>          "ticket",
                "staff"         =>          "staff",
                "blog"          =>          "blog",
                "store"         =>          "store",
                "mail"          =>          "mail",
                "campus"        =>          "campus",
                "guesthelp"     =>          "guest_help",
                "ranks"         =>          "ranks",
                "forums"        =>          "forums",
                "index"         =>          "index",
                "members"       =>          "members"   );
                

if($pages[$_GET['page']]) {
    require("code/public/code_".$pages[$_GET['page']].".php"); //Include whichever php file we want.
    $class_name = "code_".$pages[$_GET['page']];
    $code = new $class_name;
    $code->construct_page();
} else {
    require("code/public/code_index.php"); //Include whichever php file we want.
    $code = new code_index;
    $code->construct_page();
}


?>
