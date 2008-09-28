<?php
    global $water;
    global $libs;
    global $page;
    
    require_once 'libs/rabbit/rabbit.php';

    Rabbit_Construct( 'action' );
    
    if ( !isset( $_GET[ 'p' ] ) ) {
        return Redirect();
    }
    
    $p = $_GET[ 'p' ];
    $req = $_POST;
    
    Rabbit_ClearPostGet();

    $page->AttachMainElement( $p, $req );
    $page->Output();

    Rabbit_Destruct();
?>
