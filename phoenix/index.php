<?php
    /*
    This source code and all other source code files in
    this repository, unless otherwise stated, are

    Copyright (c) 2005 - 2008, Kamibu Development Group.
    
    More information can be found at /etc/legal.txt.
    
    Please leave this notice only in index.php and do not
    paste it in other files of the source code repository.
    */

    global $page;

    if ( $_SERVER[ 'REMOTE_ADDR' ] == '85.72.176.204' ) {
        define( 'WATER_ENABLE', true );
    }
    
    require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'ExcaliburHTML' );

    $req = $_GET;

    Rabbit_ClearPostGet();
    
    $page->AttachMainElement( 'main', $req );
    $page->Output();

    Rabbit_Destruct();
?>
