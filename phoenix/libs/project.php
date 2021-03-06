<?php
    class PageExcaliburHTML extends PageHTML {
        private $mTitleFinal = false;
        private $mKeywords = array();

        public function FinalizeTitle() {
            $this->mTitleFinal = true;
        }
        public function TitleFinal() {
            return $this->mTitleFinal;
        }
        public function AddKeyword( $keyword ) {
            if ( is_array( $keyword ) ) {
                $this->mKeywords = array_merge( $this->mKeywords, $keyword );
            }
            else {
                $this->mKeywords[] = $keyword;
            }
            parent::AddMeta( 'keywords', implode( ', ', $this->mKeywords ) );
        }
        public function __construct() {
            parent::__construct();
        }
    }

    class PageExcaliburiPhone extends PageExcaliburHTML {
        public function __construct() {
            parent::__construct();
            $this->AddMeta( 'viewport', "initial-scale = 1.0; user-scalable = no" );
        }
    }

    function Project_Construct( $mode ) {
        global $xc_settings;
        global $rabbit_settings;
        global $water;
        global $page;
        global $user;
        global $libs;

        $libs->Load( 'magic' );
        $libs->Load( 'user/user' );
        $libs->Load( 'user/cookie' );
        $libs->Load( 'types' );
        $libs->Load( 'sequence' );
        $libs->Load( 'adminpanel/ban' );

        $xc_settings = $rabbit_settings[ '_excalibur' ];
      
        $finder = New UserFinder();
        if ( !empty( $_SESSION[ 's_userid' ] ) && !empty( $_SESSION[ 's_authtoken' ] ) ) {
            $user = $finder->FindByIdAndAuthtoken( $_SESSION[ 's_userid' ] , $_SESSION[ 's_authtoken' ] );
            if ( $user === false ) {
                // userid/authtoken combination in session is invalid
                $user = New User( array() );
            }
        }
        else {
            $cookie = User_GetCookie();
            if ( $cookie === false ) {
                $user = New User( array() );
            }
            else {
                $userid = $cookie[ 'userid' ];
                $userauth = $cookie[ 'authtoken' ];
                $user = $finder->FindByIdAndAuthtoken( $userid, $userauth );
                if ( $user === false ) {
                    // not found
                    $water->Trace( 'No such user ' . $userid . ':' . $userauth );
                    $user = New User( array() );
                }
            }
        }

        if ( $user->Deleted ) {
            $_SESSION[ 's_username' ] = '';
            $_SESSION[ 's_password' ] = '';

            $user->RenewAuthtoken();
            $user->Save();

            User_ClearCookie();

            header( 'Location: http://static.zino.gr/phoenix/deleted' );
            exit();
        }
        
        if ( ( $user->Exists() && Ban::isBannedUser( $user->Id ) ) 
            || Ban::isBannedIp( UserIp() )  
            || !$user->HasPermission( PERMISSION_ACCESS_SITE ) ) {
            $page->AttachMainElement( 'user/banned', array() );
            $page->Output();
            exit();
        }

        if ( $user->Exists() ) {
            $libs->Load( 'user/lastactive' );
            $user->LastActivity->Save();
        }
    }
    
    function Project_Destruct() {
    }
    
    function Project_PagesMap() {
        // This function is used for matching the value of the $p variable with the actual file on the server.
        // For example $p = register matches with the user/new file.
        return array(
            ""                  => "frontpage/view",
            "bennu"             => "bennu",
            "user"              => "user/profile/view",
            "settings"          => "user/settings/view",
            "join"              => "user/join",
            "joined"            => "user/joined",
            "journals"          => "journal/list",
            "journal"           => "journal/view",
            "addjournal"        => "journal/new",
            "polls"             => "poll/list",
            "poll"              => "poll/view",
            "albums"            => "album/list",
            "album"             => "album/photo/list",
            "photo"             => "album/photo/view",
            "upload"            => "album/photo/upload",
            "friends"           => "user/relations/list",
            'legal'             => 'about/legal/view',
            'privacy'           => 'about/legal/pp',
            'tos'               => 'about/legal/tos',
            'ads'               => 'admanager/intro',
            'admanager/create'  => 'admanager/create',
            'admanager/list'    => 'admanager/list',
            'admanager/tips'    => 'admanager/tips',
            'admanager/demographics' => 'admanager/demographics',
            'admanager/checkout'=> 'admanager/checkout',
            'admanager/success' => 'admanager/success',
            'admanager/failure' => 'admanager/failure',
            'admanager/bank'    => 'admanager/bank',
            'adviewer'          => 'adminpanel/adviewer/view',
            'contact'           => 'about/contact/view',
            'unittest'          => 'developer/test/view',
            'debug'             => 'developer/water',
            'jslint'            => 'developer/js/lint',
            'a'                 => 'user/invalid',
            'b'                 => 'mail/sent',
            'pms'               => 'pm/list',
            'mostpopular'       => 'adminpanel/mostpopular/view',
            'shoutbox'          => 'shoutbox/list',
			'school'			=> 'school/view',
			'schoolmembers'		=> 'school/members/list',
            'questions'         => 'question/list',
            'answers'           => 'question/answer/list',
            'comments/recent'   => 'comment/recent/list',
            'mc'                => 'developer/memcache/view',
	        'statistics'	    => 'statistics/view',
	        'adminpanel'        => 'adminpanel/view',
            'mcdelete'          => 'developer/abresas/mcdelete',
			'favourites'		=> 'favourite/view',
            'allpolls'          => 'poll/recent/list',
            'alljournals'       => 'journal/recent/list',
            'allphotos'         => 'album/photo/recent/list',
            'search2'           => 'search',
            'search'            => 'search/view',
            'adminlog'          => 'adminlog/view',
            'banlist'           => 'banlist/view',
            'dublicate'         => 'adminpanel/dublicate/view',
            'moderateschools'   => 'adminpanel/schools/moderate',
            'contactfinder'     => 'contacts/select',
            'emailvalidate'     => 'user/settings/emailvalidate',
			'invite'            => 'contacts/page',
            'mailtosend'        => 'contacts/mailtosend',
            'interests'         => 'tag/view',
			'find'				=> 'search/im/credentials',
            'iphone/'           => 'iphone/frontpage/view',
            'iphone/user'       => 'iphone/user/profile/view',
            'recent'            => 'recent/view',
            'wysiwyg'           => 'developer/dionyziz/wysiwyg',
            'kolaz'             => 'album/photo/kolaz',
            'notvalidated'      => 'validation/page',
			'revalidate'        => 'validation/resend',
            'test'              => 'test',
            'taglist'           => 'album/photo/taglist',
			'paginationtest'	=> 'developer/petros/pagination',
			'photomanager' 		=> 'album/manager',
            'api/user'          => 'api/user',
            'api/friends'       => 'api/friends',
            'api/albums'        => 'api/albums',
            'api/album'         => 'api/album',
            'api/image'         => 'api/image',
            'api/status'        => 'api/status',
            'api/avatar'        => 'api/avatar',
            'api/auth'          => 'api/auth',
            'api/status'        => 'api/status',
            'api/notifications' => 'api/notifications',
            'store/product'     => 'store/product',
            'store/home'        => 'store/home',
            'store/thanks'      => 'store/thanks',
            'store/admin'       => 'store/admin',
            'store/manager'     => 'store/manager',
			'recommended'		=> 'spot',
            'forgot'            => 'user/passwordrequest/view',
            'forgot/success'    => 'user/passwordrequest/success',
            'forgot/failure'    => 'user/passwordrequest/failure',
            'forgot/recover'    => 'user/passwordrequest/recover',
            'spot'              => 'developer/abresas/spot',
            'about'             => 'about/info/view',
            'happeningadmin'    => 'adminpanel/happenings/view',
            'bounces'           => 'statistics/bounces',
            'applications'      => 'application/list',
            'memorystats'       => 'adminpanel/memoryabuse/view',
            'chat'              => 'chat/view',
            'dio'               => 'developer/dionyziz/attach',
            'dashboard'         => 'dashboard/view',
            'online'            => 'developer/abresas/online',
            'dionyziz'          => 'developer/dionyziz/satori',
            'notifications'     => 'notify/list'
        );
    }
    
    function Project_Events() {
        return array(
            'ImageCreated' => 'frontpage/image/new',
            'CommentCreated' => array( 
                'frontpage/comment/new', 
                'comments/page/new',
                'backend/notification/comment/created',
                'backend/spot/comment/created',
            ),
            'CommentDeleted' => 'backend/notification/comment/deleted',
            'ShoutCreated' => 'frontpage/shoutbox/new',
            'ShoutTyping' => 'frontpage/shoutbox/typing',
            'NotificationCreated' => 'frontpage/notification/new',
            'FavouriteCreated' => array(
                'backend/notification/favourite/created',
                'backend/spot/favourite/created'
            ),
            'FriendRelationCreated' => 'backend/notification/friend/created',
            'FriendRelationDeleted' => 'backend/notification/friend/deleted',
            'ImageTagCreated' => 'backend/notification/image/tag/created', 
            'ImageTagDeleted' => 'backend/notification/image/tag/deleted',
            'VoteCreated' => 'backend/spot/vote/created'
        );
    }
?>
