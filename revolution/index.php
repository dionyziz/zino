<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

    $resource = $method = '';
    !isset( $_GET[ 'resource' ] ) or $resource = $_GET[ 'resource' ];
    !isset( $_GET[ 'method' ] ) or $method = $_GET[ 'method' ];
	unset( $_GET[ 'resource' ], $_GET[ 'method' ] );

	in_array( $resource, array(
        'photo', 'session', 'comment', 'favourite'
    ) ) or $resource = 'photo';

	if ( $method == 'create' || $method == 'delete' || $method == 'update' ) {
		$vars = $_POST;
	}
	else {
		$vars = $_GET;
        $method == 'view' or $method = 'listing';
	}

	$uri = $_SERVER[ 'REQUEST_URI' ];
	$base = 'http://alpha.zino.gr' . substr( $uri, 0, strpos( $uri, '/', 1 ) );

    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    echo "<?xml-stylesheet type=\"text/xsl\" href=\"$base/xslt/$resource/$method.xsl\"?>";

    ?><social generated="<?= date( "Y-m-d H:i:s", $_SERVER[ 'REQUEST_TIME' ] ); ?>"<?php
    if ( isset( $_SESSION[ 'user' ] ) ) {
        ?> for="<?= $_SESSION[ 'user' ][ 'name' ]; ?>"<?php
    }
    ?> generator="<?= $base; ?>"><?php

    include 'controllers/' . $resource . '.php';
    call_user_func_array( $method, $vars );

    ?></social><?php
?>
