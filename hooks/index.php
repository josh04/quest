<?php
/* Hooks index
   Contains all the hooks
   Populated on hook install
   Format: Mod name (directory), file name, function to be called [(optional) admin function */

$hooks['home/header'][] = array('ie6_warning','main.php','warning');
$hooks['home/extra'][] = array('frontpage_news','main.php','frontpage_news','admin.php');