<?php
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );

    header( 'Content-type: application/xml' );

    if ( isset( $_GET[ 'resource' ] ) ) {
        $resource = $_GET[ 'resource' ];
        unset( $_GET[ 'resource' ] );
    }
    else {
        $resource = 'photo';
    }
    if ( isset( $_GET[ 'method' ] ) ) {
        $method = $_GET[ 'method' ];
        unset( $_GET[ 'method' ] );
    }
    else {
        $method = 'listing';
    }
    $resource_whitelist = array_flip( array( 'photo', 'session', 'comment', 'favourite' ) );
    $method_whitelist = array_flip( array( 'view', 'listing', 'create', 'delete', 'update' ) );
    if ( $method != 'listing' && $method != 'view' ) {
        $_SERVER[ 'REQUEST_METHOD' ] == 'POST' or die;
        $vars = $_POST;
    }
    else {
        $vars = $_GET;
    }

    isset( $resource_whitelist[ $resource ] ) or die;
    isset( $method_whitelist[ $method ] ) or die;

    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    echo '<?xml-stylesheet type="text/xsl" href="/experiment/xslt/' . $resource . '/' . $method . '.xsl"?>';
    ?><social generated="<?php
    echo date( "Y-m-d H:i:s", time() );
    ?>"<?php
    if ( isset( $_SESSION[ 'user' ] ) ) {
        ?> for="<?php
        echo $_SESSION[ 'user' ][ 'name' ];
        ?>"<?php
    }
    ?>><?php
    include 'controllers/' . $resource . '.php';
    call_user_func_array( $method, $vars );
    ?></social><?php
?>
