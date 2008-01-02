<?php
    function Project_Construct( $mode ) {
    	global $xc_settings;
    	global $page;
        global $water;
        global $page;
        global $user;
        global $libs;
        
    	$libs->Load( 'magic' );
    	$libs->Load( 'log' );
    	$libs->Load( 'user' );
        
        $xc_settings = require_once 'excalibur_settings.php';

        $libs->Load( 'memcache/mc' ); // needs xc_settings
        
    	$_SESSION[ 'previousuri' ] = ( isset ( $_SESSION[ 'thisuri' ] ) ? $_SESSION[ 'thisuri' ] : "" );
    	$_SESSION[ 'thisuri' ] = $_SERVER[ 'REQUEST_URI' ];
    	
    	if ( !empty( $_SESSION[ 's_username' ] ) && !empty( $_SESSION[ 's_password' ] ) ) {
    		CheckLogon( "session" , $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );
    	}
    	else if ( !empty( $_COOKIE[ $xc_settings[ 'cookiename' ] ] ) ) {
    		CheckLogon( "cookie" );
    	}
    	else {
    		$user = new User( array() );
    	}
    		
    	CheckIfUserBanned();

        if ( $xc_settings[ "readonly" ] <= $user->Rights() ) {
        	$log = New Log();
            $log->Save();
        }
    }
    
    function Project_Destruct() {
    }
    
    function Project_PagesMap() {
        // This function is used for matching the value of the $p variable with the actual file on the server.
        // For example $p = register matches with the user/new file.
    	return array(
    		""                 	=> "frontpage/view",
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
            'commentsearch'     => 'developer/abresas/search/comments',
            'eventsearch'       => 'developer/abresas/search/events',
            'debug'             => 'developer/water',
            'jslint'            => 'developer/js/lint'
    	);
    }
?>
