<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

    $resource = $method = '';
    !isset( $_GET[ 'resource' ] ) or $resource = $_GET[ 'resource' ];
    !isset( $_GET[ 'method' ] ) or $method = $_GET[ 'method' ];

	in_array( $resource, array(
        'photo', 'session', 'comment', 'favourite', 'poll', 'journal', 'pollvote', 'news',
        'user', 'chatmessage', 'tunnel', 'videostream', 'notification','friendship', 'interest'
    ) ) or $resource = 'photo';

	if ( $method == 'create' || $method == 'delete' || $method == 'update' ) {
		$vars = $_POST;
	}
	else {
        unset( $_GET[ 'resource' ], $_GET[ 'method' ] );
		$vars = $_GET;
        $method == 'view' or $method = 'listing';
	}
    
    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }
    
    clude( 'models/water.php' );

    global $settings;
    
    $settings = include 'settings.php';
    
	$uri = $_SERVER[ 'REQUEST_URI' ];

    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    echo "<?xml-stylesheet type=\"text/xsl\" href=\"" . $settings[ 'base' ] . "/global.xsl\"?>";

    ?><social generated="<?= date( "Y-m-d H:i:s", $_SERVER[ 'REQUEST_TIME' ] ); ?>"<?
    if ( isset( $_SESSION[ 'user' ] ) ) {
        ?> for="<?= $_SESSION[ 'user' ][ 'name' ]; ?>"<?
    }
    ?> generator="<?= $settings[ 'base' ];
    ?>" resource="<?= $resource;
    ?>" method="<?= $method;
    ?>"><?php

    clude( 'controllers/' . $resource . '.php' );
    
    $refl = New ReflectionClass( 'Controller' . $resource );
    $func = $refl->getMethod( $method );
    
    $params = array();
    
    foreach ( $func->getParameters() as $parameter ) {
        $paramname = $parameter->getName();
        if ( isset( $vars[ $paramname ] ) ) {
            $params[] = $vars[ $paramname ];
        }
        else {
            if ( !$parameter->isDefaultValueAvailable() ) {
                $params[] = null;
            }
            else { 
                $params[] = $parameter->getDefaultValue();
            }
        }
    }
    
    call_user_func_array( array( 'Controller' . $resource, $method ), $params );
    
    ?></social><?php
?>
