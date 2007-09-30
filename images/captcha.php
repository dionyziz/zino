<?php
	set_include_path( '../:./' );

    global $page;
    global $user;
    global $libs;
    global $rabbit_settings;
    global $water;
    
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct();

    $libs->Load( 'captcha' );

    if ( !isset( $_SESSION[ 'captcha' ] ) ){
        $_SESSION[ 'captcha' ] = 'CAPTCHA';
    }
    
    header( 'Content-type: image/png' );
    echo Captcha_Image( $_SESSION[ 'captcha' ] );
?>
