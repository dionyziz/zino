<?php
    /*
    This source code and all other source code files in
    this repository, unless otherwise stated, are

    Copyright (c) 2005 - 2008, Kamibu Development Group.
    
    More information can be found at /etc/legal.txt.
    
    Please leave this notice only in index.php and do not
    paste it in other files of the source code repository.
    */

    global $libs;

    // move all these into one file
    require_once 'libs/rabbit/rabbit.php';
    require_once 'libs/rabbit/primitive.php';
    $libs->Load( 'rabbit/controller' );
    
    $req = $_GET;
    Rabbit_ClearPostGet();

    // change .htaccess to rewrite everything to index.php
    // then read URI like this
    // uri = $_SERVER[ 'REQUEST_URI' ];

    // this is just for the testing phase
    $uri = $req[ 'p' ];
    
    Project_Construct();
    Controller_Fire( $uri, $req );

    // Rabbit_Destruct(); // this doesn't do anything!
?>
