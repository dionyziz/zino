<?php
    global $libs;
    global $page;
    global $rabbit_settings;
    global $coala;
    global $water;
    
    // define( 'WATER_ENABLE', false );
    
    require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'coala' );

    $water->Trace( 'Special page type: UNIT' );

    $warmable = count( $_POST ) > 0 || !$rabbit_settings[ 'production' ]; // TODO: Coala console
    $req = array_merge( $_GET, $_POST );
    
    Rabbit_ClearPostGet();
    
    $units = $coala->ParseRequest( $warmable, $req );

    $pages = array();
    foreach ( $units as $unit ) {
        $page->AttachMainElement( $unit[ 'type' ], $unit[ 'id' ], $unit[ 'req' ] );
        $pages[] = $unit[ 'type' ] . ':' . $unit[ 'id' ]; 
    }
    $water->SetPageURL( $_SERVER[ 'PHP_SELF' ] . ' - ' . implode( '; ', $pages ) );
    $page->Output();
    
    Rabbit_Destruct();
?>
