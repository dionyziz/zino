<?php
	set_include_path( '../:./' );

    global $page;
    global $user;
    global $libs;
    global $rabbit_settings;
    global $water;
    
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct();

    $libs->Load( 'dictionary' );
    $libs->Load( 'captcha' );

    // if ( !isset( $_SESSION[ 'captcha' ] ) ){
        $greek = New Dictionary( 'greek' );
        $captcha = '';
        while ( strlen( $captcha ) < 1 || strlen( $captcha ) > 10 ) {
            $captcha = $greek->GetRandomWord();
        }
        $_SESSION[ 'captcha' ] = $captcha;
    // }
    
    header( 'Content-type: image/png' );
    echo Captcha_Image( $_SESSION[ 'captcha' ] );
?>
