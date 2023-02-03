<?php

function lang($phrase){

    static $lang = array(
        'message' => 'مرحبا',
        'admin' => 'مدير'
    );

    return $lang[$phrase];
}