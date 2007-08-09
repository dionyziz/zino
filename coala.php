<?php

    global $libs;
    global $page;
    global $rabbit_settings;
    global $coala;
    
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'coala' );

    $warmable = count( $_POST ) > 0 || !$rabbit_settings[ 'production' ]; // TODO: Coala console
    $req = array_merge( $_GET , $_POST );
    
    Rabbit_ClearPostGet();
    
    $units = $coala->ParseRequest( $warmable, $req );
    foreach ( $units as $unit ) {
        $page->AttachMainElement( $unit[ 'type' ], $unit[ 'id' ], $unit[ 'req' ] );
    }
    $page->Output();
    
    Rabbit_Destruct();
?>
