<?php
    function Project_Construct( $mode ) {
    	global $xc_settings;
        global $rabbit_settings;
    	global $page;
        global $water;
        global $page;
        global $user;
        global $libs;
        
    	$libs->Load( 'magic' );
    	$libs->Load( 'user/user' );
    	$libs->Load( 'ban' );
        
        $xc_settings = $rabbit_settings[ '_excalibur' ];

        $libs->Load( 'memcache/mc' ); // needs xc_settings
        
    	$_SESSION[ 'previousuri' ] = ( isset ( $_SESSION[ 'thisuri' ] ) ? $_SESSION[ 'thisuri' ] : "" );
    	$_SESSION[ 'thisuri' ] = $_SERVER[ 'REQUEST_URI' ];
    	
        $finder = New UserFinder();
        if ( !empty( $_SESSION[ 's_username' ] ) && !empty( $_SESSION[ 's_password' ] ) ) {
            $user = $finder->FindByNameAndPassword( $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );
        }
        else if ( !empty( $_COOKIE[ $xc_settings[ 'cookiename' ] ] ) ) {
            $logininfo = $_COOKIE[ $xc_settings['cookiename'] ];
            $logininfos = explode( ':' , $logininfo );
            $userid = $logininfos[ 0 ];
            $userauth = $logininfos[ 1 ];
            if ( strlen( $userauth ) != 32 ) {
                $user = new User( array() );
            }
            else {
                $user = $finder->FindByIdAndAuthtoken( $userid, $userauth );
            }
        }
        else {
            $user = new User( array() );
        }
        
        $banned = false;
        if ( !$user->HasPermission( PERMISSION_ACCESS_SITE ) ) {
            $banned = true;
        }
        
        $banfinder = New BanFinder();
        $bans = $banfinder->FindByIp( UserIp() );
        foreach ( $bans as $ban ) {
            if ( !$ban->Expired ) {
                $banned = true;
            }
        }
        
        if ( $banned ) {
            // $page->AttachMainElement( 'user/banned', array() );
            // $page->Output();
            // exit();
            $water->Trace( 'banned' );
        }
    }
    
    function Project_Destruct() {
    }
    
    function Project_PagesMap() {
        // This function is used for matching the value of the $p variable with the actual file on the server.
        // For example $p = register matches with the user/new file.
    	return array(
    		""                 	=> "frontpage",
            "bennu"             => "bennu",
			"profile"			=> "user/profile/view",
			"join" 				=> "user/join",
			"journallist"		=> "journal/list",
			"journal"			=> "journal/view",
			"polllist"			=> "poll/list",
			"poll"				=> "poll/view",
			"albums"			=> "album/list",
			"album" 			=> "album/photo/list",
			"photo"				=> "album/photo/view",
            'unittest'          => 'developer/test/view',
            'search'            => 'developer/abresas/search',
            'commentsearch'     => 'developer/abresas/search/comments',
            'eventsearch'       => 'developer/abresas/search/events',
            'debug'             => 'developer/water',
            'jslint'            => 'developer/js/lint',
            'developer/dionyziz/console' => 'developer/dionyziz/console',
            'developer/dionyziz/sqlite' => 'developer/dionyziz/sqlite'
    	);
    }
?>
