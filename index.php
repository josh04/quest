<?php
/**
 * index.php
 *
 * calls main bootstrap
 * @package code_common
 * @author josh04
 */
 $time = microtime(true);

// ------------------------

require_once("code/common/code_questdb.php");            //Initialise database first
require_once("code/common/code_player.php");    //Get our player class.
require_once("code/common/code_common.php");
require_once("skin/common/skin_common.php");
require_once("skin/common/skin_fight.php");
require_once("code/common/code_bootstrap.php");
require_once("code/common/code_database_wrapper.php");
require_once("code/common/code_fight.php");
include("install.lock");

$bootstrap = new code_bootstrap;
$bootstrap->bootstrap();
$newtime = microtime(true);
//print ($newtime-$time);
?>
