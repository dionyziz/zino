<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    define( 'WATER_ENABLE', false );
    Rabbit_Construct();

    global $libs;

    $libs->Load( 'user/user' );
    $libs->Load( 'journal' );
    $libs->Load( 'url' );

    $usersOffset = 0;
    $finder = New UserFinder();
    do {
        $someUsers = $finder->FindAll( $usersOffset, 100 );
        $user = $finder->FindById( 1 );
        foreach ( $someUsers as $user ) {
            $urls = array();
            $journalsOffset = 0;
            $finder = New JournalFinder();
            do {
                $someJournals = $finder->FindByUser( $user, $journalsOffset, 100 );
                foreach ( $someJournals as $journal ) {
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
                for ( $i = 0; $i < count( $someJournals ); ++$i ) {
                    $someJournals[ $i ]->Url = $urls[ $i ];
                    $someJournals[ $i ]->Save();
                }
                $journalsOffset += 100;
            } while ( count( $someJournals ) );
        }
        $usersOffset += 100;
        break;
    } while ( count( $someUsers ) );

    Rabbit_Destruct();

?>
