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
    
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'HTML' );

    $req = $_GET;
    
    Rabbit_ClearPostGet();
    
    $page->AttachMainElement( 'main' , $req );
    $page->Output();

    Rabbit_Destruct();
?>
