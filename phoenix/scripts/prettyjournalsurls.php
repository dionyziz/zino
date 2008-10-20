<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    Rabbit_Construct();

    global $libs;

    $libs->Load( 'user/user' );
    $libs->Load( 'journal' );
    $libs->Load( 'url' );

    $finder = New UserFinder();
    $users = $finder->FindAll();

    foreach ( $users as $user ) {
        $urls = array();
        $finder = New JournalFinder();
        $journals = $finder->FindByUser( $user, 0, 1000000 );
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
            echo "$candidate<br />";
        }

        /*for ( $i = 0; $i < count( $journals ); ++$i ) {
            $journals[ $i ]->Url = $urls[ $i ];
            $journals[ $i ]->Save();
        }*/
    }

    Rabbit_Destruct();

?>
