<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

    $resource = '';
    if ( isset( $_GET[ 'resource' ] ) ) {
        $resource = $_GET[ 'resource' ];
        unset( $_GET[ 'resource' ] );
    }
    $method = '';
    if ( isset( $_GET[ 'method' ] ) ) {
        $method = $_GET[ 'method' ];
        unset( $_GET[ 'method' ] );
    }
    switch ( $resource ) {
        case 'photo': case 'session': case 'comment': case 'favourite':
            break;
        default:
            $resource = 'photo';
    }
    switch ( $method ) {
        case 'view': case 'listing': case 'create': case 'delete': case 'update':
            break;
        default:
            $method = 'listing';
    }
    if ( $method != 'listing' && $method != 'view' ) {
        $_SERVER[ 'REQUEST_METHOD' ] == 'POST' or die;
        $vars = $_POST;
    }
    else {
        $vars = $_GET;
    }

    $path = explode( '/', substr( $_SERVER[ 'SCRIPT_FILENAME' ], strlen( '/var/www/zino.gr/alpha/' ) ), 2 );
    $base = 'http://alpha.zino.gr/' . $path[ 0 ]; // $path[ 0 ] is the developer name
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    echo '<?xml-stylesheet type="text/xsl" href="' . $base . '/xslt/' . $resource . '/' . $method . '.xsl"?>';
    ?><social generated="<?php
    echo date( "Y-m-d H:i:s", time() );
    ?>"<?php
    if ( isset( $_SESSION[ 'user' ] ) ) {
        ?> for="<?php
        echo $_SESSION[ 'user' ][ 'name' ];
        ?>"<?php
    }
    ?> generator="<?php
    echo $base;
    ?>"><?php
    include 'controllers/' . $resource . '.php';
    call_user_func_array( $method, $vars );
    ?></social><?php
?>
