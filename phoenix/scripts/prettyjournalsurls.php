<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    global $libs;

    $libs->Load( 'user/user' );
    $libs->Load( 'journal' );
    $libs->Load( 'url' );

    $finder = New UserFinder();
    $users = $finder->FindAll( 0, 1000000 );

    foreach ( $users as $user ) {
        $urls = array();
        $finder = New JournalFinder();
        $journals = $finder->FindByUser( $user, 0, 1000000 );
        foreach ( $journals as $journal ) {
            $candidate = URL_Format( $journal->Title );
            $exists = true;
            while ( $exists ) {
                $exists = false;
                foreach ( $urls as $url ) {
                    if ( $candidate == $url ) {
                        $candidate .= '_';
                        $exists = true;
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
    }

    Rabbit_Destruct();

?>
