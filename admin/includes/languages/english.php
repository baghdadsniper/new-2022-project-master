<?php

function lang($phrase)
{
  //homepage
  static $lang = array(
    // navbar
    'HOME_ADMIN'  => 'Home',
    'CATEGORIES'  => 'Categories',
    'ITEMS'       => 'Items',
    'MEMBERS'     => 'Members',
    'STATISTICS'  => 'Statistics',
    'LOGS'        => 'Logs',
    'COMMENTS'    => 'Comments',
  );

  return $lang[$phrase];
}
