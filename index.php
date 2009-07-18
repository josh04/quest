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

require_once('adodb/adodb.inc.php'); //Include adodb files
require_once("code/common/code_player.php");    //Get our player class.
require_once("code/common/code_common.php");
require_once("skin/common/skin_common.php");
require_once("skin/common/skin_battle.php");
require_once("code/common/code_bootstrap.php");
require_once("code/common/code_database_wrapper.php");
require_once("code/common/code_battle.php");
include("install.lock");

$bootstrap = new code_bootstrap;
$bootstrap->bootstrap();
?>
