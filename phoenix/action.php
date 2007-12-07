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
    $req = array_merge( $_POST, $_FILES );
    
    Rabbit_ClearPostGet();

    $page->AttachMainElement( $p, $req );
    $page->Output();

    Rabbit_Destruct();
?>
