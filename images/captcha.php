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
        $_SESSION[ 'captcha' ] = $greek->GetRandomWord();
    // }
    
    // header( 'Content-type: image/png' );
    Captcha_Image( $_SESSION[ 'captcha' ] );

    ?><body><script type="text/javascript"><?php
    $water->GenerateJS();
    ?></script></body><?php
?>
