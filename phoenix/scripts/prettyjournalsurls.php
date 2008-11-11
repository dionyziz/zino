<?php

    $offset = $_GET[ 'offset' ];
    $limit = $offset + 100;
    $i = 0;

    set_include_path( '../:./' );

    require '../libs/rabbit/rabbit.php';

    define( 'WATER_ENABLE', false );
    Rabbit_Construct();

    global $libs;
    global $db;

    $libs->Load( 'url' );

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
        if ( !isset( $journals[ $userId ] ) ) {
            $journals[ $userId ] = array();
        }
        $journals[ $userId ][] = $journalInfo;
    }

    $result = array();
    foreach ( $journals as $userId => $hisJournals ) {
        $urls = array();
        foreach ( $hisJournals as $journalInfo ) {
            $candidate = URL_Format( $journalInfo[ 'title' ] );
            while ( isset( $urls[ $candidate ] ) ) {
                $candidate .= '_';
            }
            $urls[ $candidate ] = true;
            $result[ $journalInfo[ 'id' ] ] = $candidate;
        }
    }

    foreach ( $result as $id => $url ) {
        if ( $i >= $offset && $i <= $limit ) {
            $query = $db->Prepare(
                'UPDATE
                    :journals 
                SET
                    `journal_url` = :journal_url
                WHERE
                    `journal_id` = :journal_id
                LIMIT 1;'
            );
            $query->BindTable( 'journals' );
            $query->Bind( 'journal_url', $url );
            $query->Bind( 'journal_id', $id );
            $query->Execute();
        }
        ++$i;
    }
    if ( $limit < count( $result ) ) {
        $offset += 100;
        Redirect( "scripts/prettyjournalsurls.php?offset=$offset" )->Redirect();
    }

    Rabbit_Destruct();

?>
