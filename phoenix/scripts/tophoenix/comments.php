<?php
	set_include_path( '../../:./' );
    require_once 'libs/rabbit/rabbit.php';

    global $rabbit_settings;

    Rabbit_Construct( 'empty' );

    w_assert( $rabbit_settings[ 'production' ] === false );

    $reloaded = New Database( 'ccbetareloaded' );
    $reloaded->Connect( 'localhost' );

    $phoenix = New Database( 'ccbeta' );
    $phoenix->Connect( 'localhost' );

    if ( isset( $_GET[ 'offset' ] ) ) {
        $offset = ( int )$_GET[ 'offset' ];
    }

    $limit = 1000;

    $query = $reloaded->Prepare(
        "SELECT
            `comment_id`, 
            `comment_userid`,
            `comment_created`,
            `comment_userip`,
            `comment_text`,
            `comment_typeid`,
            `comment_storyid`, 
            `comment_parentid`
        FROM
            `merlin_comments`
        WHERE
            `comment_delid` != :delid
        LIMIT
            :offset, :limit"
    );
    $query->Bind( 'delid', 0 );
    $query->Bind( 'offset', $offset );
    $query->Bind( 'limit', $limit );
    $res = $query->Execute();

    $phoenix->Prepare(
        "TRUNCATE `merlin_comments`"
    )->Execute();

    $affected = 0;
    while ( $row = $res->FetchArray() ) {
        $query = $phoenix->Prepare(
            "INSERT INTO
                `merlin_bulk`
            (`bulk_text`) VALUES
            (:text)"
        );
        $query->Bind( 'text', $row[ 'comment_text' ] );
        $change = $query->Execute();
        $bulkid = $change->InsertId();
        
        $query = $phoenix->Prepare(
            "INSERT IGNORE INTO
                `merlin_comments`
            (`comment_id`, `comment_userid`, `comment_created`, `comment_userip`, `comment_bulkid`, `comment_itemid`, `comment_parentid`, `comment_delid`, `comment_typeid`) VALUES
            (:id, :userid, :created, :userip, :bulkid, :itemid, :parentid, :delid, :typeid)"
        );

        $query->Bind( 'id', ( int )$row[ 'comment_id' ] );
        $query->Bind( 'userid', ( int )$row[ 'comment_userid' ] );
        $query->Bind( 'created', $row[ 'comment_created' ] );
        $query->Bind( 'userip', ip2long( $row[ 'comment_userip' ] ) );
        $query->Bind( 'bulkid', $bulkid );
        $query->Bind( 'itemid', ( int )$row[ 'comment_storyid' ] );
        $query->Bind( 'parentid', ( int )$row[ 'comment_parentid' ] );
        $query->Bind( 'delid', 0 );
        $query->Bind( 'typeid', ( int )$row[ 'comment_typeid' ] );
        
        $change = $query->Execute();
        $affected += $change->AffectedRows();
    }

    echo "$affected comments inserted.";

    Rabbit_Destruct();

?>
