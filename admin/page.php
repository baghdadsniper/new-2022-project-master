<?php
/*
categories => [manage \ edit \ update \ add \ insert \ post\ delete \ status ]
*/

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
//if the page is the main page
if ($do == 'Manage') {
    echo 'welcome you are in manage category page';
    echo '<a href="?do=Add"Add New Category +</a>';
} elseif ($do == 'Add') {
    echo 'welcome you are in add category page';
} elseif ($do == 'Insert') {
    echo 'welcome you are in insert category page';
} else {
    echo 'error theres no page with this name';
}
include $tpl . 'footer.php';
