<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

    die( 'test' );

    include 'models/resource.php';
    include 'models/page.php';

    global $settings;
    $settings[ 'base' ] = 'http://alpha.zino.gr/abresas';

    list( $resource, $method, $vars ) = Resource_Init();
    Page_OutputResource( $resource, $method, $vars );

?>
