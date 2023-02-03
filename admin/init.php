<?php
include 'connect.php';
//routes

$tpl = 'includes/templates/'; // template directory
$lang = 'includes/languages/'; //language directory
$func = 'includes/functions/'; //functions directory
$css = 'layout/css/'; // css directory
$js = 'layout/js/'; // js directory


//includes the important files
include $func . 'function.php';
include $lang . 'english.php';
include $tpl . 'header.php';

//include navbar on all pages expect the one with $noNavbar variable

if(!isset($noNavbar)){
    include $tpl . 'navbar.php';
}
