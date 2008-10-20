<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    global $libs;

    $libs->Load( 'journal' );
    $libs->Load( 'url' );

    $urls = array();
    $finder = New JournalFinder();
    $journals = $finder->FindAll();
    foreach ( $journals as $journal ) {
        $candidate = URL_Format( $journal->Title );
        $exists = True;
        while ( $exists ) {
            $exists = False;
            foreach ( $urls as $url ) {
                if ( $candidate == $url ) {
                    $candidate .= '_';
                    $exists = True;
                    break;
                }
            }
        }
        $urls[] = $candidate;
    }

    for ( $i = 0; $i < count( $journals ); ++$i ) {
        $journals[ $i ]->Url = $urls[ $i ];
        $journals[ $i ]->Save();
    }

    Rabbit_Destruct();

?>
