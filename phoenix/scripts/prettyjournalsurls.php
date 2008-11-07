<?php

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    define( 'WATER_ENABLE', false );
    Rabbit_Construct();

    global $libs;
    global $db;

    /*$libs->Load( 'user/user' );
    $libs->Load( 'journal' );*/
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
    } while ( count( $someUsers ) );

    $offset = 0;
    $userFinder = New UserFinder();
    do {
        $someUsers = $userFinder->FindAll( $offset, 100 );
        foreach ( $someUsers as $user ) {
            // $urls = array();
            // $journalFinder = New JournalFinder();
            // $journals = $journalFinder->FindByUser( $user, 0, 100 );
            echo $user->Id;
            echo '<br />';
        }
        $offset += 100;
    } while ( count( $someUsers ) );

    echo 'done';*/

    $query = $db->Prepare( 'SELECT * FROM :journals' );
    $query->BindTable( 'journals' );
    $res = $query->Execute();

    $journals = array();
    while ( $row = $res->FetchArray() ) {
        $userId = $row[ 'journal_userid' ];
        $journalInfo = array(
            'id' => $row[ 'journal_id' ],
            'title' => $row[ 'journal_title' ]
        );
        if ( isset( $journals[ $userId ] ) ) {
            $journals[ $userId ][] = $journalInfo;
        }
        else {
            $journals[ $userId ] = array( $journalInfo );
        }
    }

    $urls = array();
    $result = array();
    foreach ( $journals as $userId => $hisJournals ) {
        $urls[ $userId ] = array();
        foreach ( $hisJournals as $journalInfo ) {
            $candidate = URL_Format( $journalInfo[ 'title' ] );
            while ( isset( $urls[ $userId ][ $candidate ] ) ) {
                $candidate .= '_';
            }
            $urls[ $userId ][ $candidate ] = true;
            $result[ $journalInfo[ 'id' ] ] = $candidate;
        }
    }

    foreach ( $result as $id => $url ) {
        $query = $db->Prepare( 'UPDATE :journals SET `journal_url` = :journal_url WHERE `journal_id` = :journal_id LIMIT 1' );
        $query->BindTable( 'journals' );
        $query->Bind( 'journal_url', $url );
        $query->Bind( 'journal_id', $id );
        $query->Execute();
    }

    Rabbit_Destruct();

?>
