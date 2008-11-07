<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    define( 'WATER_ENABLE', false );
    Rabbit_Construct();

    global $libs;

    $libs->Load( 'user/user' );
    $libs->Load( 'journal' );
    $libs->Load( 'url' );

    /*function process( $journal, $urls ) {
        $candidate = URL_Format( $journal->Title );
        while ( isset( $urls[ $candidate ] ) ) {
            $candidate .= '_';
        }
        $journal->Url = $candidate;
        $journal->Save();
        return $candidate;
    }

    $usersOffset = 0;
    $userFinder = New UserFinder();
    do {
        $someUsers = $userFinder->FindAll( $usersOffset, 100 );
        foreach ( $someUsers as $user ) {
            $urls = array();
            $journalFinder = New JournalFinder();
            $journals = $journalFinder->FindByUser( $user, 0, 100 );
            foreach ( $journals as $journal ) {
                $urls[ process( $journal, $urls ) ] = true;
            }
        }
        $usersOffset += 100;
    } while ( count( $someUsers ) );*/

    $offset = 0;
    $finder = New UserFinder();
    do {
        $someUsers = $finder->FindAll( $offset, 100 );
        foreach ( $someUsers as $user ) {
            echo $user->Id;
            echo '<br />';
        }
        $offset += 100;
    } while ( count( $someUsers ) );

    Rabbit_Destruct();

?>
