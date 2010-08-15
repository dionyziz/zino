<?php
    ob_start();
    
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );
    
    clude( 'models/water.php' );
    header( 'Content-type: application/xml' );

    global $settings;
    $settings = include 'settings.php';
    
	$uri = $_SERVER[ 'REQUEST_URI' ];
    
    if ( !isset( $_SESSION[ 'user' ] ) ) {
        clude( 'models/user.php' );
        $user = User::GetCookieData();
        if ( $user !== false ) {
            $_SESSION[ 'user' ] = $user;
        }
    }
    if ( isset( $_SESSION[ 'user' ] ) ) {
        switch ( strtolower( $_SESSION[ 'user' ][ 'name' ] ) ) {
            case 'shinda_tori':
            case 'beren2112':
            case 'donhoulio':
            case 'satanoulis':
            case 'annaaa':
			case 'nitrio2' :
			case strtolower( 'Stak_O_Gamiac' ) :
                die( 'Access denied' );
            default:
        }
    }
    
    $resource = $method = '';
    !isset( $_GET[ 'resource' ] ) or $resource = $_GET[ 'resource' ];
    !isset( $_GET[ 'method' ] ) or $method = $_GET[ 'method' ];

	if ( !in_array( $resource, array(
        'photo', 'session', 'comment', 'favourite', 'poll', 'journal', 'pollvote', 'news',
        'user', 'chatmessage', 'tunnel', 'videostream', 'notification','friendship', 'interest', 'settings',
        'chatchannel', 'presence', 'place', 'mood', 'album', 'song', 'imagetag', 'ban'
    ) ) ) {
        if ( isset( $_SESSION[ 'user' ] ) ) {
            $resource = 'photo';
            $method = 'listing';
        }
        else {
            $resource = 'session';
            $method = 'view';
        }
    }

	if ( $method == 'create' || $method == 'delete' || $method == 'update' ) {
		$vars = $_POST;
        $_SERVER[ 'REQUEST_METHOD' ] == 'POST' or die( 'Non-idempotent REST method cannot be applied with the idempotent HTTP request method "' . $_SERVER[ 'REQUEST_METHOD' ] . '"' );

		//check http referer
		if ( $_POST[ 'Referer' ] === "" ) {
			;//donothing
		}
		else {
			if ( filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === false ) {
				throw New Exception( 'Not Valid Post Referer' );
			}		
			$parts = parse_url( $_POST[ 'Referer' ] );
			$domain = $parts['scheme'].'://'.$parts['host'];
			if ( $domain !== $settings[ 'base' ] ) { 
				throw New Exception( 'Not Valid Post Referer' );
			}
		}
	}
	else {
        unset( $_GET[ 'resource' ], $_GET[ 'method' ] );
		$vars = $_GET;
        $method == 'view' or $method = 'listing';
	}
    
    function Template( $path, $variables ) {
        foreach ( $variables as $_name => $_value ) {
            $$_name = $_value; //MAGIC!
        }
        include 'views/' . $path . '.php';
    }
    
    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }
    
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $subdomain = isset( $_GET[ 'realsubdomain' ] ) && preg_match( '/^[a-zA-Z0-9-]*$/', $_GET[ 'realsubdomain' ] );
    if ( $subdomain ) {
        echo '<?xml-stylesheet type="text/xsl" href="http://' . $_GET[ 'realsubdomain' ] . '.zino.gr/hack.xsl"?>';
    }
    else {
        echo "<?xml-stylesheet type=\"text/xsl\" href=\"" . $settings[ 'base' ] . "/global.xsl?" . $settings[ 'cachecontrol' ][ 'xslversion' ] . "\"?>";
    }
    ?><social generated="<?= date( "Y-m-d H:i:s", $_SERVER[ 'REQUEST_TIME' ] ); ?>"<?
    if ( isset( $_SESSION[ 'user' ] ) ) {
        ?> for="<?= $_SESSION[ 'user' ][ 'name' ]; ?>"<?
    }
    ?> generator="<?= $subdomain ? 'http://' . $_GET[ 'realsubdomain' ] . '.zino.gr' : $settings[ 'base' ];
    ?>" resource="<?= $resource;
    ?>" method="<?= $method;
    ?>"><?php

    clude( 'controllers/' . $resource . '.php' );
    
    $refl = New ReflectionClass( 'Controller' . $resource );
    $func = $refl->getMethod( $method );
    
    $params = array();
    
    $paramlist = $func->getParameters();
    
    if ( !empty( $paramlist ) && $paramlist[ 0 ]->getName() == 'multiargs' ) {
        /* pass arguments compacted */
        $params[ 'multiargs' ] = $vars;
    }
    else {
        foreach ( $paramlist as $parameter ) {
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
    }
    try {
        call_user_func_array( array( 'Controller' . $resource, $method ), $params );
    }
    catch ( Exception $e ) {
        echo '<error>' . $e->getMessage() . '</error>';
    }
    
    ?></social><?php
    
    ob_flush();
?>
