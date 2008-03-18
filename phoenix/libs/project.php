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
    	$libs->Load( 'log' );
    	$libs->Load( 'user' );
        
        $xc_settings = $rabbit_settings[ '_excalibur' ];

        $libs->Load( 'memcache/mc' ); // needs xc_settings
        
    	$_SESSION[ 'previousuri' ] = ( isset ( $_SESSION[ 'thisuri' ] ) ? $_SESSION[ 'thisuri' ] : "" );
    	$_SESSION[ 'thisuri' ] = $_SERVER[ 'REQUEST_URI' ];
    	
        if ( false ) {
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
        	
            $banfinder = New BanFinder();
            $banned = false;
            if ( $user->Banned ) {
                $banned = true;
            }
            
            $ban = $banfinder->FindByIp( UserIp() );
            if ( $ban !== false && !$ban->Expired ) {
                $banned = true;
            }
            
            if ( $banned ) {
                return Element( 'user/banned' );
            }
			
		    if ( $xc_settings[ "readonly" ] <= $user->Rights() ) {
				$log = New Log();
				$log->Save();
			}
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
