<?php
	set_include_path( '../:./' );

    global $page;
    global $user;
    global $libs;
    global $rabbit_settings;
    global $water;
    
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct();

    if ( !isset( $_SESSION[ 'captcha_image' ] ) ){
        return;
    }
    
    header( 'Content-type: image/png' );
    
    echo $_SESSION[ 'captcha_image' ];
?>
