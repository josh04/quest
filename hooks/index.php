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