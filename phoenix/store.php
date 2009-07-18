<?php
    global $page;

    require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'ExcaliburHTML' );

    $req = $_GET;

    Rabbit_ClearPostGet();
    
    $page->AttachMainElement( 'store/main', $req );
    $page->Output();

    Rabbit_Destruct();
?>
