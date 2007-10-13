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
            'latestimages'
            // 'logs',
            // 'memcache'
        ),
        8 => array( 
            'monitor',
            'notify'
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
    fwrite( $handle, "-- (excalibur backup ends here)\n" );
    fclose( $handle );

    echo "done<br />";
    echo "step " . $step . "/14";

    header( "Location: http://www.chit-chat.gr/scripts/backup2.php?step=" . $step + 1 );
    exit;
    
    Rabbit_Destruct();
?>
