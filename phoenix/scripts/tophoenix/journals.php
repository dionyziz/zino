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
            `revision_title`, `revision_textid`, `revision_creatorid`,
            `article_created`, `article_numcomments`, `article_numviews`,
            `article_id`, `article_headrevision`, `revision_id`
        FROM
            `merlin_articles` 
            CROSS JOIN `merlin_revisions`
                ON `article_id` = `revision_articleid`
        WHERE
            `article_typeid` = :TypeId
            AND `revision_minor` = :Minor
            AND `article_delid` = :Deleted
        GROUP BY
            `article_id`, `revision_userid`"
    );
    $query->Bind( 'TypeId', ARTICLE_TYPE_NORMAL );
    $query->Bind( 'Minor', 'no' );
    $query->Bind( 'Deleted', 0 );
    
    $res = $query->Execute();
    
    $revisions = array();
    $articles = array();
    
    while ( $row = $res->FetchArray() ) {
        if ( !isset( $revisions[ $row[ 'article_id' ] ] ) ) {
            $revisions[ $row[ 'article_id' ] ] = array();
        }
        $revisions[ $row[ 'article_id' ] ][ $row[ 'revision_id' ] ] = array_intersect_key(
            $row,
            array(
                'revision_title' => true,
                'revision_textid' => true,
                'revision_creatorid' => true,
                'revision_id' => true 
            )
        );
        $articles[ $row[ 'article_id' ] ] = array_intersect_key(
            $row,
            array(
                'article_id' => true,
                'article_created' => true, 
                'article_numcomments' => true, 
                'article_numviews' => true,
                'article_headrevision' => true
            )
        );
    }
    
    $numjournals = 0;
    $numrevisions = 0;
    
    foreach ( $articles as $articleid => $article ) {
        $articlerevisions = $revisions[ $article[ 'article_id' ] ];
        $query = $phoenix->Prepare(
            "INSERT INTO
                `merlin_journals`
            (`journal_created`, `journal_delid`, `journal_numcomments`, `journal_numviews`, `journal_title`, `journal_bulkid`) VALUES
            (:Created, :DelId, :NumComments, :NumViews, :Title, :BulkId)"
        );
        $query->Bind( 'Created', $article[ 'article_created' ] );
        $query->Bind( 'DelId', 0 );
        $query->Bind( 'NumComments', $article[ 'article_numcomments' ] );
        $query->Bind( 'NumViews', $article[ 'article_numviews' ] );
        $query->Bind( 'Title', $articlerevisions[ $article[ 'article_headrevision' ] ][ 'revision_title' ] );
        $query->Bind( 'BulkId', $articlerevisions[ $article[ 'article_headrevision' ] ][ 'revision_textid' ] );
        
        $change = $query->Execute();
        $journalid = $change->InsertId();
        
        foreach ( $articlerevisions as $revision ) {
            $query = $phoenix->Prepare(
                "INSERT INTO
                    `merlin_journalauthors`
                (`journalauthor_journalid`, `journalauthor_userid`) VALUES
                (:JournalId, :UserId)"
            );
            $query->Bind( 'JournalId', $journalid );
            $query->Bind( 'UserId', $revision[ 'revision_creatorid' ] );
            $query->Execute();
            ++$numrevisions;
        }
        ++$numjournals;
    }
    
    echo "$numjournals of $numrevisions authors migrated.";
?>
