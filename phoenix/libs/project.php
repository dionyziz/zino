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
        $libs->Load( 'user/cookie' );
        $libs->Load( 'ban' );
		$libs->Load( 'types' );
        
        $xc_settings = $rabbit_settings[ '_excalibur' ];
      
        $finder = New UserFinder();
        if ( !empty( $_SESSION[ 's_username' ] ) && !empty( $_SESSION[ 's_password' ] ) ) {
            $user = $finder->FindByNameAndPassword( $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );
            if ( $user === false ) {
                // username/password combination in session is invalid
                $user = new User( array() );
            }
        }
        else {
            $cookie = User_GetCookie();
            if ( $cookie === false ) {
                $user = new User( array() );
            }
            else {
                $userid = $cookie[ 'userid' ];
                $userauth = $cookie[ 'authtoken' ];
                $user = $finder->FindByIdAndAuthtoken( $userid, $userauth );
                if ( $user === false ) {
                    // not found
                    $water->Trace( 'No such user ' . $userid . ':' . $userauth );
                    $user = new User( array() );
                }
            }
        }

        $banned = false;
        if ( !is_object( $user ) && UserIp() == ip2long( '92.253.19.82' ) ) {
            ob_start();
            var_dump( $user );
            echo ob_get_clean();
            die( 'dead!' );
        }
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
            $page->AttachMainElement( 'user/banned', array() );
            $page->Output();
            exit();
        }

        if ( $user->Exists() ) {
            $user->LastActivity->Save();
        }
    }
    
    function Project_Destruct() {
    }
    
    function Project_PagesMap() {
        // This function is used for matching the value of the $p variable with the actual file on the server.
        // For example $p = register matches with the user/new file.
    	return array(
    		""                 	=> "frontpage/view",
			"comment"			=> "comment/test",
            "bennu"             => "bennu",
			"user"			    => "user/profile/view",
			"settings"			=> "user/settings/view",
			"join" 				=> "user/join",
			"joined"			=> "user/joined",
			"journals"			=> "journal/list",
			"journal"			=> "journal/view",
			"addjournal"		=> "journal/new",
			"polls"				=> "poll/list",
			"poll"				=> "poll/view",
			"albums"			=> "album/list",
			"album" 			=> "album/photo/list",
			"photo"				=> "album/photo/view",
			"upload" 			=> "album/photo/upload",
            'tos'               => 'about/tos/view',
            'unittest'          => 'developer/test/view',
            'search'            => 'developer/abresas/search',
            'commentsearch'     => 'developer/abresas/search/comments',
            'eventsearch'       => 'developer/abresas/search/events',
            'debug'             => 'developer/water',
            'jslint'            => 'developer/js/lint',
            'developer/dionyziz/console' => 'developer/dionyziz/console',
            'developer/dionyziz/sqlite' => 'developer/dionyziz/sqlite',
            'developer/dionyziz/contacts' => 'developer/dionyziz/contacts',
            'developer/dionyziz/utf8' => 'developer/dionyziz/utf8'
    	);
    }
?>
