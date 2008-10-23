<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    define( 'WATER_ENABLE', false );
    Rabbit_Construct();

    global $libs;

    $libs->Load( 'user/user' );
    $libs->Load( 'journal' );
    $libs->Load( 'url' );

    $finder = New JournalFinder();
    $journal = $finder->FindById( 2189 );
    $journal->Url = URL_Format( $journal->Title );
    $journal->Save();
    echo 'done';

    Rabbit_Destruct();

?>
