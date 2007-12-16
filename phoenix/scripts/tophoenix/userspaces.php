<?php
	set_include_path( '../../:./' );
    require_once 'libs/rabbit/rabbit.php';

    define('ARTICLE_TYPE_NORMAL', 0);
    define('ARTICLE_TYPE_RESERVED', 1);
    define('ARTICLE_TYPE_USERSPACE', 2);
    
    global $rabbit_settings;

    Rabbit_Construct( 'empty' );

    w_assert( $rabbit_settings[ 'production' ] === false );

    $reloaded = New Database( 'ccbetareloaded' );
    $reloaded->Connect( 'localhost' );

    $phoenix = New Database( 'ccbeta' );
    $phoenix->Connect( 'localhost' );

    $query = $reloaded->Prepare(
        "SELECT
            `user_id`, `revision_textid`, `revision_updated`
        FROM
            `merlin_users` 
            CROSS JOIN `merlin_articles` 
                ON `user_blogid` = `article_id`
            CROSS JOIN `merlin_revisions`
                ON (`article_id` = `revision_articleid`
                    AND `revision_id` = `article_headrevision`)
        WHERE
            `article_typeid` = :TypeId
            AND `article_delid` = :Deleted
        GROUP BY
            `article_id`, `revision_userid`"
    );
    $query->Bind( 'TypeId', ARTICLE_TYPE_USERSPACE );
    $query->Bind( 'Deleted', 0 );
    $res = $query->Execute();
    
    $numspaces = 0;
    
    while ( $row = $res->FetchArray() ) {
        $query = $phoenix->Prepare(
            "INSERT INTO
                `merlin_userspaces`
            (`space_userid`, `space_bulkid`, `space_updated`) VALUES
            (:UserId, :BulkId, :Updated)"
        );
        $query->Bind( 'UserId', $row[ 'user_id' ] );
        $query->Bind( 'BulkId', $row[ 'revision_textid' ] );
        $query->Bind( 'Updated', $row[ 'revision_updated' ] );
        $query->Execute();
        ++$numspaces;
    }
    
    echo "$numspaces spaces migrated.";
?>
