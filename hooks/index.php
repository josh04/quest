<?php
/* Hooks index
   Contains all the hooks
   Populated on hook install
   Format: Mod name (directory), file name, function to be called [(optional) admin function */

$hooks['home/extra'][] = array('frontpage_stats','stats.php','stats_table');
$hooks['home/extra'][] = array('frontpage_users_online','online.php','online');
$hooks['home/extra'][] = array('frontpage_log','log.php','log_box');
$hooks['home/extra'][] = array('frontpage_mail','mail.php','mail_box');

$hooks['home/extra'][] = array('frontpage_news','main.php','frontpage_news','admin.php');
$hooks['home/header'][] = array('frontpage_quest','quest','quest');

$hooks['player/make/pre'][] = array('player_profile','profile.php','pre_make_player');
$hooks['player/make/post'][] = array('player_profile','profile.php','post_make_player');
$hooks['player/get/pre'][] = array('player_profile','profile.php','pre_make_player');
$hooks['player/get/post'][] = array('player_profile','profile.php','post_make_player');
$hooks['player/update'][] = array('player_profile','profile.php','update');


$hooks['player/make/pre'][] = array('player_rpg','rpg.php','rpg_pre_make_player');
$hooks['player/make/post'][] = array('player_rpg','rpg.php','rpg_post_make_player');
$hooks['player/get/pre'][] = array('player_rpg','rpg.php','rpg_pre_make_player');
$hooks['player/get/post'][] = array('player_rpg','rpg.php','rpg_post_make_player');
$hooks['player/update'][] = array('player_rpg','rpg.php','rpg_update');
$hooks['player/create'][] = array('player_rpg','rpg.php','rpg_create');