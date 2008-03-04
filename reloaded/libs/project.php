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
        	LogThis();
        }
    }
    
    function Project_Destruct() {
    }
    
    function Project_OnBeforeSessionStart() {
        global $xc_settings;
        
        die( 'setting params' );
        session_set_cookie_params( 24 * 3600, '/', $xc_settings[ 'cookiedomain' ] );
    }
    
    function Project_PagesMap() {
        // This function is used for matching the value of the $p variable with the actual file on the server.
        // For example $p = register matches with the user/new file.
    	return array(
    		""                 	=> "frontpage/view",
    		"register"         	=> "user/new",
    		"k"                	=> "user/created", 
    		"a"                	=> "user/invalid",
    		"oust"				=> "user/loginbot",
    		"nc"               	=> "category/new",
    		"addstory"         	=> "article/new/view",
    		"editstory"        	=> "article/new/view",
    		"story"            	=> "article/view",
    		"revisions"         => "article/revision/view",
    		"editoradd"         => "article/editoradd",
    		"p"                	=> "user/options/view",
    		"user"             	=> "user/profile/view",
    		"useradmin"        	=> "user/admin",
    		"category"         	=> "category/view",
    		"lostpassword"     	=> "user/lostpassword",
            "chpasswd"          => "user/changepassword",
    		"pms"              	=> "pm/list",
			"pmsnew" 			=> "pm/new/list",
    		"search"           	=> "search/search",
    		"userlist"         	=> "user/list",
    		"questions"        	=> "question/list",
    		"places"           	=> "place/list" ,
    		"chat"             	=> "chat/view" ,
            'r0x0r'             => "chat/callisto" ,
    		"su"               	=> "admin/su" ,
    		"tos"              	=> "user/tos" ,
    		"emoticons"        	=> "media/emoticons/list" ,
    		"newadmins"		   	=> "admin/newadmins" ,
    		"debug"            	=> "developer/water" ,
            'unittest'          => 'developer/test/view' ,
    		"allshouts"		   	=> "shout/latest" ,
    		"album"			   	=> "album/view",
    		"allarticles"	   	=> "article/archive",
    		"photo"			   	=> "photo/view",
    		"faq"			   	=> "faq/popular",
    		"faqq"				=> "faq/question/view",
    		"faqc"				=> "faq/category/view",
    		"faqs"				=> "faq/search/view",
    		"addfaqc"			=> "faq/category/new",
    		"addfaqq"			=> "faq/question/new",
            'editspace'         => 'userspace/edit',
    		"notifytest" 		=> "notify/test",
            "uploadframe"       => "photo/uploadform",
    		"graphs"			=> "graph/view",
			"frel"	            => "relations/list",
			"userbans"			=> "user/ban/list",
            'test'              => 'test',
            'jslint'            => 'developer/js/lint',
            'poll'              => 'poll/view',
			'advertise' 		=> 'advertise/info',
			'uniadmin'			=> 'universities/create',
			'tag'				=> 'interesttag/view',
            'wysiwyg'           => 'developer/dionyziz/wysiwyg',
            'photolist'         => 'photo/list'
    	);
    }
?>
