<?php
    global $page;

    define( 'WATER_ENABLE', false );

    require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'ExcaliburiPhone' );

    $req = $_GET;

    Rabbit_ClearPostGet();
    
    if ( !isset( $req[ 'p' ] ) ) {
        $req[ 'p' ] = '';
    }
    $req[ 'p' ] = 'iphone/' . $req[ 'p' ];
    $page->AttachMainElement( 'iphone/main', $req );
    $page->Output();

    Rabbit_Destruct();
?>
