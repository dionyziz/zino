<?
    session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );
    header( 'Content-type: application/xhtml+xml' );
    
    $vars = $_POST;
    
    function include_fast( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }
    
    include_fast( 'models/water.php' );

    global $settings;
    
    $settings = include 'settings.php';
    
    include_fast( 'models/db.php' );
    include_fast( 'models/user.php' );
    
    if ( isset( $vars[ 'username' ] ) && isset( $vars[ 'password' ] ) ) {
        $data = User::Login( $vars[ 'username' ], $vars[ 'password' ] );
        $success = $data !== false;
    }
    else {
        $success = false;
    }
    if ( $success ) {
        $_SESSION[ 'user' ] = $data;
        header( 'Location: ' . $settings[ 'base' ] );
    }
    else {
        header( 'Location: ' . $settings[ 'base' ] . '/wrongpass' );
    }
    
?>