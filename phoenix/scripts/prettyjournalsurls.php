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
    $userFinder = New UserFinder();
    do {
        $someUsers = $userFinder->FindAll( $usersOffset, 100 );
        foreach ( $someUsers as $user ) {
            $urls = array();
            $journalsOffset = 0;
            $journalFinder = New JournalFinder();
            do {
                $someJournals = $journalFinder->FindByUser( $user, $journalsOffset, 100 );
                foreach ( $someJournals as $journal ) {
                    $candidate = URL_Format( $journal->Title );
                    while ( isset( $urls[ $candidate ] ) ) {
                        $candidate .= '_';
                    }
                    $urls[ $candidate ] = true;
                    $journal->Url = $candidate;
                    $journal->Save();
                }
                $journalsOffset += 100;
            } while ( count( $someJournals ) );
        }
        $usersOffset += 100;
    } while ( count( $someUsers ) );

    function process( $journal, $urls ) {
        // TODO
    }

    Rabbit_Destruct();

?>
