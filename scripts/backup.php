<?php
    global $page, $db, $rabbit_settings, $user;
    
    if ( $rabbit_settings[ 'production' ] ) {
        die( 'This script may only run in a non-production environment!' );
    }
    
    $limit = isset( $_GET[ 'full' ] ) ? '': ' LIMIT 5';
    
	set_include_path( '../:./' );
	require_once 'libs/rabbit/rabbit.php';
    
    Rabbit_Construct( 'Empty' );

    if ( !$user->IsSysOp() ) {
        die( "Could you kindly fuck off?" );
    }

    $reloaded = New Database( 'excalibur-sandbox' );
    $reloaded->Connect( 'localhost' );
    $reloaded->Authenticate( 'excalibursandbox' , 'viuhluqouhoa' );
    $reloaded->SetCharset( 'DEFAULT' );

    function Slashify( &$value, $key ) {
        $value = addslashes( $value );
    }
    
    $backup = array(
        /* 'articles',
        'ipban',
        'bulk',
        'categories',
        // 'chat',
        'comments',
        'faqquestions',
        'faqcategories',
        // 'friendrel',
        'images',
        // 'logs',
        // 'memcache',
        'pms',
        // 'pmfolders',
        // 'pmmessageinfolder',
        // 'pmmessages',  */
        'profile',
        'pageviews',
        'places',
        // 'polls',
        // 'polloptions',
        'profileq',
        'relations',
        'revisions',
        'ricons',
        // 'searches',
        'shoutbox',
        // 'starring',
        // 'templates',
        'userban',
        'users',
        // 'usershout',
        'articles',
        'revisions',
        // 'vars',
        // 'votes',
        'albums',
        // 'notify'
    );
    
    $page->Output();

    header( 'Content-type: text/plain' );
    header( 'Content-Disposition: attachment; filename=' . $rabbit_settings[ 'applicationname' ] . '-' . NowDate() . '.sql' );
    
    echo '-- Excalibur backup as of ' . NowDate() . "\n";
    foreach ( $backup as $table ) {
        $res = $reloaded->Query( 'SELECT * FROM `' . $rabbit_settings[ 'databases' ][ 'db' ][ 'prefix' ] . $table . '`' . $limit . ';' );
        echo "-- Backing up table `$table`\n";
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
                 . '" );' . "\n";
        }
    }
    echo "-- (excalibur backup ends here)\n";
    
    Rabbit_Destruct();
?>
