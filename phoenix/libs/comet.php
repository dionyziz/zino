<?php
    define( 'COMET_PUBLISHING_SERVER', '147.102.133.127' ); // 'universe.zino.gr' );
    define( 'COMET_PUBLISHING_PORT', 4671 );
    define( 'COMET_PUBLISHING_TIMEOUT', 5 );
    
    class CometException extends Exception {
    }
    
    /*
    function Comet_Publish( $channel, $message ) {
        w_assert( preg_match( '#[A-Za-z0-9_]+#', $channel ) );
        w_assert( preg_match( '#^[A-Za-z0-9_{}\\[\\]\\\\~.|"\',!@\\#:$%^&*()+= -]*$#', $message ) );
        
        $fh = fsockopen( COMET_PUBLISHING_SERVER, COMET_PUBLISHING_PORT, $errno, $errstr, COMET_PUBLISHING_TIMEOUT );
        if ( !$fh ) {
            throw New CometException( 'Could not initiate socket connection to publishing server: ' . $errstr . ' (' . $errno . ')' );
        }
        fwrite( $fh, "ADDMESSAGE $channel $message\n" );
        $response = '';
        while ( !feof( $fh ) ) {
            $response .= fread( $fh, 8192 );
        }
        fclose( $fh );
    }
    */
?>
