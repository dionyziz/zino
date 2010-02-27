<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

    include 'models/resource.php';
    include 'models/page.php';

    global $settings;
    $path = explode( '/', substr( $_SERVER[ 'SCRIPT_FILENAME' ], strlen( '/var/www/zino.gr/alpha/' ) ), 2 );
    $settings[ 'base' ] = 'http://alpha.zino.gr/' . $path[ 0 ];

    list( $resource, $method, $vars ) = Resource_Init();
    Page_OutputSocialResource( $resource, $method, $vars );

?>
