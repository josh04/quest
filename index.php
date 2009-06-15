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

// (TODO) this should be db, help modders
// BUT HOW?!?!
$pages = array( "login"         =>          "login",
                "stats"         =>          "stats",
                "laptop"        =>          "log",
                "inventory"     =>          "inventory",
                "bank"          =>          "bank",
                "hospital"      =>          "hospital",
                "work"          =>          "work",
                "help"          =>          "help",
                "profile_edit"  =>          "edit_profile",
                "battle"        =>          "battle",
                "profile"       =>          "profile",
                "ticket"        =>          "ticket",
                "staff"         =>          "staff",
                "store"         =>          "store",
                "mail"          =>          "mail",
                "campus"        =>          "campus",
                "guesthelp"     =>          "guest_help",
                "ranks"         =>          "ranks",
                "index"         =>          "index",
                "members"       =>          "members"   );

$path = "public";
$page = "index";
if (!file_exists('install.lock')) {
    $path = "install";
    require("code/install/code_install.php");
    $pages = array( "index"             =>          "start",
                    "database"          =>          "database",
                    "user"              =>          "user",
                    "upgrade_database"  =>          "upgrade_database"    );

}

if ($pages[$_GET['page']]) {
    $page = $_GET['page'];
}

require("code/".$path."/code_".$pages[$page].".php"); //Include whichever php file we want.
$class_name = "code_".$pages[$page];
$code = new $class_name;
$code->construct_page();
?>
