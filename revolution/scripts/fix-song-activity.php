<?php

    include 'models/music/grooveshark.php';
    include 'models/db.php';

    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }

    $gsapi = GSAPI::getInstance(array('APIKey' => "1100e42a014847408ff940b233a39930" ) );
    $info = $gsapi->songAbout( 5017002 );
    var_dump( $info );

?>
