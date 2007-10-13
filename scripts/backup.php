<?php
    global $page, $db, $rabbit_settings, $user;
    
    if ( $rabbit_settings[ 'production' ] ) {
        die( 'This script may only run in a non-production environment!' );
    }
    
    $limit = isset( $_GET[ 'full' ] ) ? '': ' LIMIT 5';
    
	set_include_path( '../:./' );
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'HTML' );

    if ( !$user->IsSysOp() ) {
        die( "Could you kindly fuck off?" );
    }

    function Slashify( &$value, $key ) {
        $value = addslashes( $value );
    }
    
    $backup = array(
        'albums',
        'articles',
        'bulk',
        'categories',
        'chat',
        'comments',
        'faqquestions',
        'faqcategories',
        'friendrel',
        'images',
        'imagetags',
        'interesttags',
        'ipban',
        'latestimages',
        'logs',
        'memcache',
        'monitor',
        'notify',
        'pageviews',
        'places',
        'pms',
        'pmfolders',
        'pmmessageinfolder',
        'pmmessages',
        'polloptions',
        'polls',
        'profilea',
        'profileq',
        'profiles',
        'relations',
        'revisions',
        'ricons',
        'searches',
        'shoutbox',
        'starring',
        'templates',
        'userban',
        'users',
        'usershout',
        'vars',
        'votes'
    );
    
    ob_start();

    header( 'Content-type: text/plain' );
    header( 'Content-Disposition: attachment; filename=' . $rabbit_settings[ 'applicationname' ] . '-' . NowDate() . '.sql' );
    echo '-- Excalibur backup as of ' . NowDate() . "<br />";
    for ( $i = 0; $i < count( $backup ); ++$i ) {
        $table = $backup[ $i ];
        $res = $db->Query( 'SELECT * FROM `' . $rabbit_settings[ 'databases' ][ 'db' ][ 'prefix' ] . $table . '`' . $limit . ';' );
        echo "-- Backing up table `$table`<br />";
        $fields = false;
        while ( $row = $res->FetchArray() ) {
            if ( $fields === false ) {
                $fields = array_keys( $row );
            }
            array_walk( $row, 'Slashify' );
            echo 'INSERT INTO `' . $rabbit_settings[ 'databases' ][ 'db' ][ 'prefix' ] . $table . '` ( `'
                 . implode( '`, `', $fields ) 
                 . '` ) VALUES ( "' 
                 . implode( '", "', $row ) 
                 . '" );' . "<br />";
        }
     }
    echo "-- (excalibur backup ends here)<br />";
    
    Rabbit_Destruct();
?>
