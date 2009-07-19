<?php
    global $page;

    require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'ExcaliburHTML' );

    $req = $_GET;

    Rabbit_ClearPostGet();
    
    if ( isset( $req[ p ] ) ) {
        $req[ 'p' ] = 'store/' . $req[ 'p' ];
    }
    else {
        $req[ 'p' ] = 'store';
    }
    
    $page->AttachMainElement( 'store/main', $req );
    $page->Output();

    Rabbit_Destruct();
?>
