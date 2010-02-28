<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

	$resources = array( 'photo', 'session', 'comment', 'favourite' );

	$resource = @$_GET[ 'resource' ];
	$method = @$_GET[ 'method' ];
	
	unset( $_GET[ 'resource' ], $_GET[ 'method' ] );

	in_array( $resource, $resources ) or $resource = 'photo';

	if ( $method == 'create' || $method == 'delete' || $method == 'update' ) {
		$vars = $_POST;
	}
	else {
		$vars = $_GET;
		if ( $method != 'view' && $method != 'listing' ) {
			$method = 'listing';
		}
	}

	$uri = $_SERVER[ 'REQUEST_URI' ];
	$base = 'http://alpha.zino.gr' . substr( $uri, 0, strpos( $uri, '/', 1 ) );

    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    echo "<?xml-stylesheet type=\"text/xsl\" href=\"$base/xslt/$resource/$method.xsl\"?>";

    ?><social generated="<?= date( "Y-m-d H:i:s", time() ); ?>"<?php
    if ( isset( $_SESSION[ 'user' ] ) ) {
        ?> for="<?= $_SESSION[ 'user' ][ 'name' ]; ?>"<?php
    }
    ?> generator="<?= $base; ?>"><?php

    include 'controllers/' . $resource . '.php';
    call_user_func_array( $method, $vars );

    ?></social><?php
?>
