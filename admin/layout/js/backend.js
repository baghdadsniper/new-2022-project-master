$(function() {

    'use strict';

    //hide placeholder on form focus

    $('[placeholder]').focus(function(){
    
        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');
    
    }).blur(function() {

        $(this).attr('placeholder', $(this).attr('data-text'));
    
    });

    //add asterisk on required field
    $('input').each(function(){
        if($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        };
    });

    // convert password field to text field
    $('.show-pass').hover(function (){

var passField = $('.password');
passField.attr('type', 'text');
    }, function (){

        passField.attr('type', 'password');


    });

    //confirmation message on button
    $('.confirm').click(function () {
        return confirm('are you sure ?');
    })
});