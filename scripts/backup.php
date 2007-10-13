<?php
    global $page, $db, $rabbit_settings, $user;
    
    if ( $rabbit_settings[ 'production' ] ) {
        die( 'This script may only run in a non-production environment!' );
    }
    
	set_include_path( '../:./' );
	require_once 'libs/rabbit/rabbit.php';

    $step = isset( $_GET[ "step" ] ) ? $_GET[ "step" ] : 0;
    
    Rabbit_Construct( 'HTML' );

    if ( !$user->IsSysOp() ) {
        die( "Could you kindly fuck off?" );
    }

    function Slashify( &$value, $key ) {
        $value = addslashes( $value );
    }
    
    $backup = array(
        0 => array(
                'albums',
                'articles'
        ),
        1 => array(
            'bulk'
        ),
        2 => array( 
            'categories',
            'chat'
        ),
        3 => array(
            'comments'
        ),
        4 => array(
            'dictionaries',
            'dictionarywords'
        ),
        5 => array(
            'faqquestions',
            'faqcategories',
            'friendrel'
        ),
        6 => array(
            'images'
        ),
        // 'imagetags',
        // 'interesttags',
        7 => array( 
            'ipban',
            'latestimages',
            // 'logs',
            // 'memcache'
            'monitor',
            'notify'
        ),
        8 => array( 
            'pageviews'
        ),
        9 => array(
            'places',
            'pms'
        ),
        // 'pmfolders',
        // 'pmmessageinfolder',
        // 'pmmessages',
        10 => array( 'polloptions',
            'polls',
            'profilea',
            'profileq'
        ),
        // 'profiles',
        11 => array(
            'relations',
            'revisions',
            'ricons'
        ),
        12 => array(
            'searches',
            'shoutbox'
        ),
        13 => array(
            'starring',
            'templates',
            'universities',
            'userban'
        ),
        14 => array(
            'users',
            'usershout',
            'vars',
            'votes'
        )
    );
    
    ob_start();

    // echo "Starting backup..";
   
    if ( $step == 0 ) {
        if ( !$handle = fopen( "ccbackup.sql", "w" ) ) {
            die( "error opening file" );
        }
        fwrite( $handle, "USE cclive;" );
    }
    else {
        if ( !$handle = fopen( "ccbackup.sql", "a" ) ) {
            die( "error opening file" );
        }
    }

    fwrite( $handle, '-- Excalibur backup as of ' . NowDate() . "\n" );
    echo "starting backup<br />";
    for ( $i = 0; $i < count( $backup[ $step ] ); ++$i ) {
        $table = $backup[ $step ][ $i ];
        // echo $table;
        $start = 0;
        $limit = 5000;
        fwrite( $handle, "-- Backing up table `$table`\n" );
        echo $table . "...";
        do {
            $res = $db->Query( "SELECT 
                                    * 
                                FROM 
                                    `" . $rabbit_settings[ 'databases' ][ 'db' ][ 'prefix' ] . $table . "`
                                LIMIT
                                    $start, $limit;" );

            $fields = false;
            $results = $res->Results();
            while ( $row = $res->FetchArray() ) {
                if ( $fields === false ) {
                    $fields = array_keys( $row );
                }
                array_walk( $row, 'Slashify' );
                fwrite( $handle, 'INSERT INTO `' . $rabbit_settings[ 'databases' ][ 'db' ][ 'prefix' ] . $table . '` ( `'
                     . implode( '`, `', $fields ) 
                     . '` ) VALUES ( "' 
                     . implode( '", "', $row ) 
                     . '" );' . "\n" );
            }
            $start += $limit;
        } while ( $results );
        echo "done<br />";
     }
    if ( $step == 14 ) {
        fwrite( $handle, "
        UPDATE `merlin_albums` SET `album_created` = `album_created` + INTERVAL 9 HOUR; UPDATE `merlin_articles` SET `article_created` = `article_created` + INTERVAL 9 HOUR; UPDATE `merlin_categories` SET `category_created` = `category_created` + INTERVAL 9 HOUR; UPDATE `merlin_comments` SET `comment_created` = `comment_created` + INTERVAL 9 HOUR; UPDATE `merlin_faqcategories` SET `faqcategory_created` = `faqcategory_created` + INTERVAL 9 HOUR; UPDATE `merlin_faqquestions` SET `faqquestion_created` = `faqquestion_created` + INTERVAL 9 HOUR; UPDATE `merlin_friendrel` SET `frel_created` = `frel_created` + INTERVAL 9 HOUR; UPDATE `merlin_images` SET `image_created` = `image_created` + INTERVAL 9 HOUR; UPDATE `merlin_notify` SET `notify_created` = `notify_created` + INTERVAL 9 HOUR; UPDATE `merlin_places` SET `place_updatedate` = `place_updatedate` + INTERVAL 9 HOUR; UPDATE `merlin_pms` SET `pm_created` = `pm_created` + INTERVAL 9 HOUR; UPDATE `merlin_polls` SET `poll_created` = `poll_created` + INTERVAL 9 HOUR;UPDATE `merlin_profilea` SET `profile_date` = `profile_date` + INTERVAL 9 HOUR; UPDATE `merlin_profileq` SET `profileq_created` = `profileq_created` + INTERVAL 9 HOUR; UPDATE `merlin_relations` SET `relation_created` = `relation_created` + INTERVAL 9 HOUR; UPDATE `merlin_revisions` SET `revision_updated` = `revision_updated` + INTERVAL 9 HOUR; UPDATE `merlin_ricons` SET `ricon_date` = `ricon_date` + INTERVAL 9 HOUR; UPDATE `merlin_searches` SET `search_date` = `search_date` + INTERVAL 9 HOUR; UPDATE `merlin_shoutbox` SET `shout_created` = `shout_created` + INTERVAL 9 HOUR; UPDATE `merlin_users` SET `user_created` = `user_created` + INTERVAL 9 HOUR; UPDATE `merlin_universities` SET `uni_createdate` = `uni_createdate` + INTERVAL 9 HOUR; UPDATE `merlin_votes` SET `vote_date` = `vote_date` + INTERVAL 9 HOUR;
        " ); // update dates
    }
    fwrite( $handle, "-- (excalibur backup ends here)\n" );
    fclose( $handle );

    echo "done<br />";
    echo "step " . $step . "/14";

    header( "Location: http://www.chit-chat.gr/scripts/backup2.php?step=" . $step + 1 );
    exit;
    
    Rabbit_Destruct();
?>
