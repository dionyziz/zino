<?php
    global $water;
    global $libs;
    global $page;
    global $rabbit_settings;

    // define( 'WATER_ENABLE', false );

    require_once 'libs/rabbit/rabbit.php';

    Rabbit_Construct( 'action' );

    if ( !isset( $_GET[ 'p' ] ) ) {
        return Redirect();
    }

    if ( $_SERVER[ 'REQUEST_METHOD' ] != 'POST' ) {
        return Redirect();
    }

    if ( !empty( $_SERVER[ 'HTTP_REFERER' ] ) ) {
        if ( isset( $rabbit_settings[ 'legalreferers' ] ) ) {
            if ( $rabbit_settings[ 'legalreferers' ] === true ) {
                // all referers are legal
            }
            else if ( $rabbit_settings[ 'legalreferers' ] === false ) {
                // all referers are illegal
                throw new Exception( 'All referers are illegal' );
            }
            else {
                w_assert( is_string( $rabbit_settings[ 'legalreferers' ] ) );
                if ( !preg_match( $rabbit_settings[ 'legalreferers' ], $_SERVER[ 'HTTP_REFERER' ] ) ) {
                    throw new Exception( $_SERVER[ 'HTTP_REFERER' ] . ' is not a valid HTTP referer' );
                }
            }
        }
        else {
            if ( strtolower( substr( $_SERVER[ 'HTTP_REFERER' ], 0, strlen( $rabbit_settings[ 'webaddress' ] ) ) ) != strtolower( $rabbit_settings[ 'webaddress' ] ) ) {
                throw New Exception( $_SERVER[ 'HTTP_REFERER' ] . ' is not a valid HTTP referer (non-local)' );
            }
        }
    }
    
    $water->Trace( 'Special page type: ACTION' );

    $p = $_GET[ 'p' ];
    $req = array_merge( $_POST, $_FILES );

    $water->SetPageURL( $_SERVER[ 'PHP_SELF' ] . ' - ' . $p );
    
    Rabbit_ClearPostGet();

    $page->AttachMainElement( $p, $req );
    $page->Output();

    Rabbit_Destruct();
?>
