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
 

require_once("code/common/code_bootstrap.php");

require_once("code/common/interfaces/basic_player.php");
include("install.lock");

$bootstrap = new code_bootstrap;
$bootstrap->bootstrap();
$newtime = microtime(true);
//print ($newtime-$time);
?>
