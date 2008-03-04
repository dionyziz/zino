<?php
	/*MASKED:
		Interest Tags (kostis90gr)
	*/
	global $libs;
    
    $libs->Load( 'image/image' ); // for usericons
    $libs->Load( 'color' );
    
    function User_ByUsername( $usernames ) {
        global $db;
        global $users;
        global $images;
        
        if ( is_array( $usernames ) ) {
            $wasarray = true;
        }
        else {
            $wasarray = false;
            $usernames = array( $usernames );
        }
        
        if ( !count( $usernames ) ) {
            return array();
        }
        
        foreach ( $usernames as $i => $username ) {
            $usernames[ $i ] = addslashes( $username );
        }
        
        $sql = "SELECT 
                    `user_id`, `user_name`, `user_subdomain`,
                    `image_id`, `image_userid`
                FROM 
                    `$users` LEFT JOIN `$images`
                        ON `user_icon` = `image_id`
                WHERE 
                    `user_name` IN ('" . implode( "', '", $usernames ) . "');";
        $res = $db->Query( $sql );
        
        $rows = array();
        while ( $row = $res->FetchArray() ) {
            $rows[ strtolower( $row[ 'user_name' ] ) ] = New User( $row );
        }
        
        if ( $wasarray ) {
            return $rows;
        }
        if ( count( $rows ) ) {
            return array_shift( $rows );
        }
        return false;
    }
    
	function User_DeriveSubdomain( $username ) {
		/* RFC 1034 - They must start with a letter, 
		end with a letter or digit,
		and have as interior characters only letters, digits, and hyphen.
		Labels must be 63 characters or less. */
		$username = strtolower( $username );
		$username = preg_replace( '/([^a-z0-9-])/i', '-', $username ); //convert invalid chars to hyphens
		$pattern = '/([a-z]+)([a-z0-9-]*)([a-z0-9]+)/i';
		if ( !preg_match( $pattern, $username, $matches ) ) {
			return false;
		}
		return $matches[0];
	}
	
    function User_BySubdomain( $subdomain ) {
        global $db;
        global $users;
		
		if ( strlen( $subdomain ) < 1 ) {
			return false;
		}
		$subdomain = myescape( $subdomain );
		
        $sql = "SELECT 
                    `user_id`, `user_name`
                FROM 
                    `$users`
                WHERE 
                    `user_subdomain` = '$subdomain'
				LIMIT 1;";
        $res = $db->Query( $sql );
        
        $rows = array();
        if ( $row = $res->FetchArray() ) {
            return New User( $row[ 'user_id' ] );
        }
        
        return false;
	}
	
	function User_IpBan( $ip, $time, $sysopid = '' ) {
		global $user;
		global $bans;
		global $db;
		
		if ( empty( $sysopid ) ) {
			$sysopid = $user->Id();
		}
		
		$date = NowDate();
		$expiredate = gmdate("Y-m-d H:i:s", time() + ( $time ) );
		
		$insert = array(
			'ipban_id' => '',
			'ipban_ip' => $ip,
			'ipban_date' => NowDate(),
			'ipban_expiredate' => $expiredate,
			'ipban_sysopid' => $sysopid
		);
		
		return $db->Insert( $insert , $bans );
	}
    
    function MakeUser( $username , $password , $email ) {
        global $xc_settings;
		global $users;
		global $db;
		global $mc;
		$reserved = Array(	//TODO: cleanup and improve this array
		/*	"blog" , "invitations" , "cube" , "blogcube" ,
			"accounts" , "profiles" , "staff" , "hades" ,
			"guardian" , "turing" , "news" , "help" ,
			"doc" , "documentation" , "admin" , "mysql" , 
			"database" , "db" , "dbase" , "access" ,
			"servers" , "status" , "blogger", */
			'anonymous', 'www', 'beta' );
		
        w_assert( $xc_settings[ "allowregisters" ] );
		
		$s_username = $username;
		w_assert( preg_match( "/^[A-Za-z][A-Za-z0-9_\-]+$/" , $username ) ); // Make sure the username contains valid characters
		$subdomain = myescape( User_DeriveSubdomain( $username ) ); //is already escaped, but may be empty
		$username = myescape( $username );
		if ( mystrtolower( $username ) == "anonymous" ) { // The username anonymous is not allowed
			// reserved
			return 2;
		}
		if ( strlen( $subdomain ) < 1 || in_array( $subdomain , $reserved ) ) {
			//subdomain is reserved, or too small (either too small username or nothing valid came up)
			return 2;
		}
		$sql = "SELECT * FROM `$users` WHERE `user_name`='$username' OR `user_subdomain`='$subdomain' LIMIT 1;";
		$sqlresult = $db->Query( $sql );
		if ( $sqlresult->Results() ) { // If there is someone with the same username or subdomain
			return 2;
		}
		if ( $email != "" ) {
			$sql = "SELECT * FROM `$users` WHERE `user_email`='$email' LIMIT 1;";
			$sqlresult = $db->Query( $sql );
			if ( $sqlresult->NumRows() ) { // If there is someone with the same email
				return 3;
			}
		}
		$ip = UserIp();
		$ip = addslashes( $ip );

        // for security against spambot automated account creation
        $sql = "SELECT 
                    COUNT(*) AS numusers
                FROM
                    `$users`
                WHERE
                    `user_registerhost` = '$ip'
                    AND `user_created` + INTERVAL 15 MINUTE > NOW()";
        $sqlresult = $db->Query( $sql );
        $row = $sqlresult->FetchArray();
        if ( $row[ 'numusers' ] >= 2 ) {
            return 5; // too many users from the same IP during the last 15 minutes
        }
        
		$password = md5( $password );
		$s_password = $password;
		$password = addslashes( $password );
		$email = addslashes( $email );
		$sql = "INSERT INTO `$users` ( `user_id` , `user_name` , `user_password` , `user_created` , `user_registerhost` , `user_lastlogon` , `user_rights` , `user_email` , `user_subdomain` , `user_signature` , `user_icon` , `user_msn` , `user_yim` , `user_aim` , `user_icq` , `user_skype` )
							   VALUES( '' , '$username' , '$password' , NOW(), '$ip' , '$nowdate' , '30' , '$email' , '$subdomain' , '' , '' ,             ''    , ''    , ''    , '' , '' );";
		$db->Query( $sql );
		$_SESSION[ 's_username' ] = $s_username;
		$_SESSION[ 's_password' ] = $s_password;
		
		// invalidate list of latest users cache
		$mc->delete( 'latestusers' );
		
		// success
		return 1;
	}	

	function UpdateUser( $signature , $newpassword , $email , $gender , $dob , $slogan , $place, $msn = false, $skype = false, $yim = false, $aim = false , $icq = false , $gtalk = false, $height = false, $weight = false, $eyecolor = false, $haircolor = false ) {
		global $users;
		global $user;
		global $db;
		
		if ( !$user->IsAnonymous() ) {
			$signature = myescape( strip_tags( $signature ) );
			$uid = $user->Id();
			if ( $newpassword == "" ) {
				$newpassword = $user->Password();
			}
			else {
				$newpassword = md5( $newpassword );
			}
			
			$email		= myescape( $email );
			$msn 		= ( $msn !== false ) ? myescape( $msn ) : myescape( $user->MSN() );
			$skype		= ( $skype !== false ) ? myescape( $skype ) : myescape( $user->Skype() );
			$yim		= ( $yim !== false ) ? myescape( $yim ) : myescape( $user->YIM() );
			$aim		= ( $aim !== false ) ? myescape( $aim ) : myescape( $user->AIM() );
			$icq		= ( $icq !== false ) ? myescape( $icq ) : myescape( $user->ICQ() );
			$gtalk		= ( $gtalk !== false ) ? myescape( $gtalk ) : myescape( $user->Gtalk() );
			$gender		= myescape( $gender );
			$dob		= myescape( $dob );
			$slogan		= myescape( $slogan );
			$place		= myescape( $place );
			
			if ( $height === false ) {
				$height = myescape( $user->Height() );
			}
			else {
				$height		= myescape( $height );
			}
			if ( $weight === false ) {
				$weight = myescape( $user->Weight() );
			}
			else {
				$weight		= myescape( $weight );
			}
			if ( $eyecolor === false ) {
				$eyecolor = myescape( $user->EyeColor() );
			}
			else {
				$eyecolor	= myescape( $eyecolor );
			}
			if ( $haircolor === false ) {
				$haircolor = myescape( $user->HairColor() );
			}
			else {
				$haircolor 	= myescape( $haircolor );
			}
			
			if ( $gender != "-" && $gender != "male" && $gender != "female" ) {
				return 3;
			}
			else {
			}
			
			$sql = "UPDATE 
						`$users` 
					SET 
						`user_signature`='$signature',
						`user_password`='$newpassword',
						`user_email`='$email', 
						`user_msn`='$msn',
						`user_skype`='$skype',
						`user_yim`='$yim',
						`user_aim`='$aim',
						`user_icq`='$icq',
						`user_gtalk`='$gtalk',
						`user_gender`='$gender',
						`user_dob`='$dob',
						`user_subtitle`='$slogan',
						`user_place`='$place',
						`user_height`='$height',
						`user_weight`='$weight',
						`user_eyecolor`='$eyecolor',
						`user_haircolor`='$haircolor'
					WHERE 
						`user_id`='$uid' 
					LIMIT 1;";
			$db->Query( $sql );
			$s_password = $newpassword;
			$_SESSION[ 's_password' ] = $s_password;
			return 1;
		}
		else {
			return 2;
		}
	}

	function UpdateTemplate( $username, $template) {
		global $users;
		global $user;
		global $db;
 
		$template = myescape($template);
		$username = myescape($username);

		if ( !$user->IsAnonymous() ) {
			if ( $user->Id() != $username ) {
				return 3;
			}

			$sql = "UPDATE 
					`$users` 
					SET 
						`user_templateid`='$template'
					WHERE 
						`user_id`='$username' 
				
					LIMIT 1;";

			$db->Query( $sql );

			return 1;
		}
		else {
			return 2;
		}
	}
	
	function AdminUser( $id , $rights ) {
		global $users;
		global $user;
		global $db;
		
		if ( $user->CanModifyCategories() ) {
			$id = myescape( $id );
			$rights = myescape( $rights );
			if ( $rights > $user->Rights() ) {
				return 3;
			}
			if( !( $user->CanModifyUsers() ) && ( $rights < 10 || $rights > 30 ) ) {
				return 6;
			}
			$sql = "SELECT * FROM `$users` WHERE `user_id`='$id' LIMIT 1;";
			$sqlresult = $db->Query( $sql );
			if ( $sqlresult->NumRows() == 0 ) {
				return 4;
			}
			$sqluser = $sqlresult->FetchArray();
			$theuser = new User( $sqluser ); // TODO: this call and constructor should change
			if ( !( $user->CanModifyUsers() ) && $user->Rights() <= $theuser->Rights() ) {
				return 5;
			}
			$sql = "UPDATE `$users` SET `user_rights`='$rights' WHERE `user_id`='$id' LIMIT 1;";
			$db->Query( $sql );
			return 1;
		}
		else {
			return 2;
		}
	}
	
	function getNewUsers() {
		global $db;
		global $users;
		global $mc;
        global $water;
        global $xc_settings;
		
		$latestusers = $mc->get( $key = 'latestusers' );
		
		if ( !is_array( $latestusers ) ) {
			$sql = "SELECT 
                        *, ( `user_created` " . $xc_settings[ "mysql2phpdate" ] . " ) AS `user_cutedate`
					FROM 
						`$users` 
					WHERE
						`user_rights` > 3
					ORDER BY 
						`user_created` 
					DESC 
					LIMIT 5;";
			
            $res = $db->Query( $sql );
			$latestusers = array();
			while ( $row = $res->FetchArray() ) {
                $water->Trace( "user construct for new users", $row );
				$latestusers[] = New User( $row );
			}
			$mc->add( $key , $latestusers );
		}
		
		return $latestusers;
	}
	
	function findOnlineUsers() {
		global $db;
		global $users;
		global $images;
        global $water;
        
		$nowdate = NowDate();
        
		$sql = "SELECT 
                    * 
				FROM 
                    `$users` LEFT JOIN `$images`
                        ON `user_icon` = `image_id`
				WHERE 
                    ( '$nowdate' - INTERVAL 5 MINUTE ) < `user_lastactive` 
				ORDER BY 
                    `user_lastactive` DESC;";

		$res = $db->Query( $sql );
		$ret = array();
		while ( $row = $res->FetchArray() ) {
			$ret[] = New User( $row );
		}
		
		return $ret;
	}
	
	function ListAllUsersByRights( $showlocked = false , $offset , $length = 50 ) {
		global $users;
		global $db;
	
		$conditions = "";
		if ( !$showlocked ) {
			$conditions = " WHERE `user_locked`!='yes' ";
		}
		if ( $offset != 0 ) {
			$offset = $offset * $length - $length;
		}
		
		$sql = "SELECT
					*
				FROM
					`$users` 
				
				$conditions 
				
				ORDER BY 
					`user_rights` DESC, 
					`user_name` ASC
				LIMIT " . $offset . " , " . $length . "
				;";
				
		$res = $db->Query( $sql );
		$ret = array();
		while ( $row = $res->FetchArray() ) {
			$ret[] = New User( $row );
		}
		
		return $ret;
	}
		
	function ListAllUsers() {
		global $users;
		global $db;
		
		$sql = "SELECT 
					`user_id`,
					`user_name`,
                    `user_subdomain`
				FROM 
					`$users`
				ORDER BY
					`user_name` ASC;";
		
		$res = $db->Query( $sql );
		$ret = $res->MakeArray();
		
		return $ret;
	}
	function CountUsers() {	
		global $users;
		global $db;
		
		$sql = "SELECT
					COUNT( * )
				AS numusers
				FROM
					`$users`
				WHERE 
					`user_locked`='no';";
		$res = $db->Query( $sql );
		$num = $res->FetchArray( $res );
		$num = $num[ "numusers" ];
		
		return $num;
	}
	function UserAddIcon( $jpgbinary ) {
		global $user;
		
		$uname = mystrtolower( $user->Username() );
		UserSetIcon( AddImage( $jpgbinary , "usericon_$uname" ) );
	}
	
	function UserSetIcon( $imageid ) {
		global $user;
		global $users;
		global $db;
		
		$uid = $user->Id();
		$sql = "UPDATE
					`$users` 
				SET
					`user_icon`='$imageid'
				WHERE
					`user_id`='$uid'
				LIMIT 1;";
		$db->Query( $sql );
	}
	
	function UserRemoveIcon() {
		global $users;
		global $user;
		global $db;
		
		$uid = $user->Id();
		$uid = myescape( $uid );
		$sql = "UPDATE
					`$users`
				SET
					`user_icon`=''
				WHERE
					`user_id`='$uid'
				LIMIT 1;";
		$db->Query( $sql );
	}
	
	function CheckLogon( $logontype , $s_username = '' , $s_password = '' ) {
		global $users;
        global $images;
		global $user;
		global $db;
		global $xc_settings;
		
		if ( $logontype == "session" ) {
            $s_username = myescape( $s_username );
            $s_password = myescape( $s_password );
            
			$sql = "SELECT 
						*, ( `user_created` " . $xc_settings[ "mysql2phpdate" ] . " ) AS `user_cutedate`

					FROM 
						`$users` LEFT JOIN `$images`
                            ON `user_icon` = `image_id`
					WHERE 
						`user_name`='$s_username' 
						AND `user_password`='$s_password' 
						AND `user_locked`!='yes' 
					LIMIT 1;";
			$res = $db->Query( $sql );
			if ( $res->Results() ) {
				$sqluser = $res->FetchArray();
				$user = new User( $sqluser );
				$nowdate = NowDate();
				$sql = "UPDATE 
							`$users` 
						SET 
							`user_lastactive`='$nowdate' 
						WHERE 
							`user_id`='" . $user->Id() . "' 
						LIMIT 1;";
				$db->Query( $sql );
			}
			else {
				$user = new User( "" );
			}
		}
		else if ( $logontype == "cookie" ) {
			if ( isset( $_COOKIE[ $xc_settings['cookiename'] ] ) ) {
				$logininfo = $_COOKIE[ $xc_settings['cookiename'] ];
				$logininfos = explode( ':' , $logininfo );
				$userid = $logininfos[ 0 ];
				$userauth = $logininfos[ 1 ];
                if ( strlen( $userauth ) != 32 ) {
                    $user = new User( array() );
                    return;
                }
				$userid = myescape( $userid );
				$userauth = myescape( $userauth );
				$sql = "SELECT 
							* 
						FROM 
							`$users` LEFT JOIN `$images`
                                ON `user_icon` = `image_id`
						WHERE 
							`user_id`='$userid' 
							AND `user_authtoken`='$userauth' 
							AND `user_locked`!='yes' 
						LIMIT 1;";
				$sqlresult = $db->Query( $sql );
				$sqluser = $sqlresult->FetchArray();
				if ( $sqluser ) {
					$_SESSION[ 's_username' ] = $sqluser[ 'user_name' ];
					$_SESSION[ 's_password' ] = $sqluser[ 'user_password' ];
					$user = new User( $sqluser );
					$nowdate = NowDate();
					$sql = "UPDATE 
								`$users` 
							SET 
								`user_lastactive`='$nowdate' 
							WHERE 
								`user_id`='" . $user->Id() . "' 
							LIMIT 1;";
					$db->Query( $sql );
				}
				else {
					$user = new User( array() );
                    return;
				}
			}
			else {
				// anonymous 
				$user = new User( array() );
                return;
			}
		}
	}
	
	function CheckIfUserBanned() {
		global $bans;
		global $user;
		global $db;
		global $water;
        
        if ( !is_object( $user ) ) {
            $water->ThrowException( 'Uninitialized user object' );
        }
        
		if ( !$user->CanModifyUsers() ) {
			$ip = UserIp();
			$sql = "SELECT 
						* 
					FROM 
						`$bans` 
					WHERE 
						`ipban_ip`='$ip' 
					LIMIT 1;";
			$res = $db->Query( $sql );
			$userban = $res->Results();
			if ( !$userban && $user->HasBeenBanned() ) { // automatically ban IP if the user's rank is below userlevel
				$insert = array(
					'ipban_id' => '',
					'ipban_ip' => UserIp(),
					'ipban_date' => NowDate(),
					'ipban_expiredate' => '0000-00-00 00:00:00',
					'ipban_sysopid' => '0'
				);
				
				$db->Insert( $insert , $bans );
				$userban = true;
			}
			if ( $userban === true ) {
				die( 'Banned. Go away.' );
			}
		}
	}

	function ActivateUserShout() {
		global $user;
		global $users;
		global $db;

		$sql = "UPDATE 
					`$users` 
				SET 
					`user_shoutboxactivated`='yes' 
				WHERE 
					`user_id`='" . $user->Id() . "' 
				LIMIT 1;";

		$db->Query( $sql );

	}
	
	function getTodayBirthdays() {
		global $users;
		global $db;
		global $mc;
		
		$nowdate = date( "m-d", time() );
		
		$birthdays = $mc->get( $key = 'birthdays:' . $nowdate ); // no need to invalidate, let it expire
		if ( !is_array( $birthdays ) ) {
			$sql = "SELECT 
						`user_name`, `user_id`, `user_rights` , `user_subdomain`
					FROM 
						`$users` 
					WHERE 
						DATE_FORMAT(`user_dob`,'%m-%d')='$nowdate';";
			
			$res = $db->Query( $sql );
			
            $birthdays = array();
            while ( $row = $res->FetchArray() ) {
                $birthdays[] = New User( $row );
            }

			$mc->add( $key , $birthdays );
		}
		
		return $birthdays;
	}

	final class User {
		private $mId;
		private $mUsername;
		private $mPassword;
		private $mLastLogon;
		private $mCreated;
		private $mLastYear, $mLastMonth, $mLastDay;
		private $mLastHour, $mLastMinute, $mLastSecond;
		private $mCreateYear, $mCreateMonth, $mCreateDay;
		private $mCreateHour, $mCreateMinute, $mCreateSecond;
		private $mCreateSince;
		private $mLastSince;
		private $mRegisterDate;
		private $mLogonDate;
		private $mRights;
		private $mSignature;
		private $mIcon;
		private $mEmail;
		private $mMSN;
		private $mSkype;
		private $mYIM;
		private $mAIM;
		private $mICQ;
		private $mGTalk;
		private $mGender;
		private $mFullContributions;
		private $mSmallNewsCount;
		private $mImagesCount;
		private $mAvatar, $mAvatarCaption;
		private $mPositionPoints;
		private $mDOB;
		private $mDOBd, $mDOBm, $mDOBy;
		private $mHobbies;
		private $mSubtitle;
		private $mRegisterHost;
		private $mQuestions, $mQIndex;
		private $mAnsweredQuestions; // count
        private $mUnansweredQuestions;
		private $mPlace;
		private $mLocation;
		private $mLocationLoaded;
		private $mBlog;
		//private $mLowRes;
		private $mLPE;
		private $mLocked;
		private $mLastActive;
		private $mActiveSince;
		private $mShoutboxActivated;
		private $mPageviews;
		private $mArticlesPageviews;
		private $mPopularity;
		private $mContribs;
		private $mGotAnsweredQuestions;
		private $mArticlesNum;
		private $mNumComments;
		private $mAuthtoken;
		private $mHeight;
		private $mWeight;
		private $mEyeColor;
		private $mHairColor;
        private $mProfileColor;
		private $mNumSmallNews;
		private $mNumImages;
        private $mUniid;
        private $mFrel_type; // If the instance is a friend of the actual user
		private $mSubdomain;
		
		public function Href() {
			return 'user/' . $this->Username();
		}
		public function Title() {
			// $comment->Page()->Title()
			return $this->Username();
		}
        public function Age() {
            $nowdate = getdate();
            $nowyear = $nowdate[ "year" ];

            return $nowyear - $this->mCreateYear;
        }
        public function Creation() {
            return $this->mCreated;
        }
		public function Locked() {
			return $this->mLocked;
		}
		public function Position() {
			return $this->mPositionPoints;
		}
		public function LPE() {
			return $this->mLPE;
		}
		public function HasSpecialRank() {
			return ( $this->Rights() != 10 );
		}
		public function Rank() {
			return RankToText( $this->Rights() );
		}
		public function IsBanned() {
			return ( $this->Rights() < 10 );
		}
		public function HasntBeenBanned() {
			return ( $this->Rights() >= 10 );
		}
		public function HasBeenBanned() {
			if( $this->mId != 0 && $this->Rights() < 10 ) {
				return true;
			}
		}
		public function HasShoutboxActivated() {
			return $this->mShoutboxActivated; 
		}
		public function IsModerator() {
			return ( $this->Rights() >= 20 );
		}
		public function CanModifyStories() {
			return ( $this->Rights() >= 30 );
		}
		public function CanModifyCategories() {
			return ( $this->Rights() >= 40 );
		}
		public function CanModifyUsers() {
			return ( $this->Rights() >= 50 );
		}
		public function IsSysOp() {
			return ( $this->Rights() >= 60 );
		}
		public function Rights() {
			return $this->mRights;
		}
		public function Username() {
			return $this->mUsername;
		}
		public function Password() {
			return $this->mPassword;
		}
		public function Subdomain() {
			return $this->mSubdomain;
		}
		public function IsAnonymous() {
			return ( $this->Username() == "" );
		}
		public function Id() {
			return $this->mId;
		}
		public function Signature() {
			return $this->mSignature;
		}
		public function Icon() {
			return $this->mIcon;
		}
		public function Email() {
			return $this->mEmail;
		}
		public function CreateSince() {
			return $this->mCreateSince;
		}
		public function RegistrationDate() {
			return $this->mRegisterDate;
		}
		public function LogonDate() {
			return $this->mLogonDate;;
		}
		public function LogonSince() {
			return $this->mLastSince;
		}
		function ActiveSince() {
				return $this->mActiveSince;
		}
		public function RegisterSince() {
			return $this->mCreateSince;
		}
		public function MSN() {
			return $this->mMSN;
		}
		public function Skype() {
			return $this->mSkype;
		}
		public function YIM() {
			return $this->mYIM;
		}
		public function AIM() {
			return $this->mAIM;
		}
		public function ICQ() {
			return $this->mICQ;
		}
		public function GTalk() {
			return $this->mGTalk;
		}
		public function Gender() { 
			return $this->mGender;
		}
		public function Contributions() {
			return $this->mContribs;
		}
		public function FullContributions() {
			return $this->mFullContributions;
		}
		public function EditContributions() {
			return $this->mFullContributions - $this->mContributions;
		}
		public function Avatar() {
			return $this->mAvatar;
		}
		public function AvatarTitle() {
			return $this->mAvatarCaption;
		}
		public function DaysSinceRegister() {
			return $this->mDaysSinceRegister;
		}
		public function DateOfBirthDay() {
			return $this->mDOBd;
		}
		public function DateOfBirthMonth() {
			return $this->mDOBm;
		}
		public function DateOfBirthYear() {
			return $this->mDOBy;
		}
		public function DateOfBirth() {
			return $this->mDOB;
		}
		public function Hobbies() {
            global $libs;

			if ( $this->mHobbies === false ) {
				$libs->Load( 'interesttag' );
				$tags = InterestTag_List( $this );
                $hobbies = array();
				foreach ( $tags as $tag ) {
                    $hobbies[] = $tag->Text;
				}
				$this->mHobbies = implode(', ', $hobbies);
			}
			return $this->mHobbies;
		}
		public function Subtitle() {
			return $this->mSubtitle;
		}
		public function Blog() {
			return $this->mBlog;
		}
		public function SetPassword( $newpassword ) {
			global $users;
			global $db;
			
			$newpassword = md5( $newpassword );
			$sql = "UPDATE `$users` SET `user_password`='$newpassword' WHERE `user_id`='" . $this->Id() . "' LIMIT 1;";
			$db->Query( $sql );
			
		}
		public function Template() {
			return $this->mTemplate;
		}
		public function Contribs() {
			return $this->mContribs;
		}
        public function RenewAuthtoken() {
            global $db;
            global $users;
            
            // generate authtoken
            // first generate 16 random bytes
            // generate 8 pseurandom 2-byte sequences 
            // (that's bad but generally conventional pseudorandom generation algorithms do not allow very high limits
            // unless they repeatedly generate random numbers, so we'll have to go this way)
            $bytes = array(); // the array of all our 16 bytes
            for ( $i = 0; $i < 8 ; ++$i ) {
                $bytesequence = rand(0, 65535); // generate a 2-bytes sequence
                // split the two bytes
                // lower-order byte
                $a = $bytesequence & 255; // a will be 0...255
                // higher-order byte
                $b = $bytesequence >> 8; // b will also be 0...255
                // append the bytes
                $bytes[] = $a;
                $bytes[] = $b;
            }
            // now that we have 16 "random" bytes, create a string of 32 characters,
            // each of which will be a hex digit 0...f
            $authtoken = ''; // start with an empty string
            foreach ( $bytes as $byte ) {
                // each byte is two authtoken digits
                // split them up
                $first = $byte & 15; // this will be 0...15
                $second = $byte >> 4; // this will be 0...15 again
                // convert decimal to hex and append
                // order doesn't really matter, it's all random after all
                $authtoken .= dechex($first) . dechex($second);
            }
			
            $sql = "UPDATE `$users` SET `user_authtoken` = '$authtoken' WHERE `user_id` = '" . $this->Id() . "' LIMIT 1;";
            $db->Query( $sql );
			
			$this->mAuthtoken = $authtoken;
        }
		public function AddContrib() {
			global $users;
			global $db;
			
            $sql = "UPDATE `$users` SET `user_contribs` = `user_contribs` + 1 WHERE `user_id`='" . $this->Id() . "';";

			$change = $db->Query( $sql );

            if ( $change->Impact() ) {
               ++$this->mContribs;

               return true;
            }
            return false;
		}
		public function RemoveContrib() {
			global $users;
			global $db;
			
			$sql = "UPDATE `$users` SET `user_contribs` = `user_contribs` - 1 WHERE `user_id` = '" . $this->Id() . "';";

			$change = $db->Query( $sql );

            if ( $change->Impact() ) {
                --$this->mContribs;

                return true;
            }
            return false;
		}
		public function Description() {
			// TODO: This function should be turned into an element
			if ( $this->HasSpecialRank() ) {
				return $this->Rank();
			}
			elseif ( $this->mContributions ) {
				return $this->AvatarTitle();
			}
			else {
				return "Νέος Χρήστης";
			}
		}
		public function RegisterHost() {
			return $this->mRegisterHost;
		}
		public function Place() {
			return $this->mPlace;
		}
		public function Location() {
			global $places;
			global $db;
			
			if ( !$this->mLocationLoaded ) {
                $place = New Place( $this->mPlace );
				$this->mLocation = $place->Name;
				$this->mLocationLoaded = true;
			}
			return $this->mLocation;
		}
		public function Uni() {
			global $libs;
			
			$libs->Load( 'universities' );
			if ( !$this->mUniLoaded ) {
				if ( $this->mUniid != 0 ) {
					$this->mUni = new Uni( $this->mUniid );
				}
				else {
					$this->mUni = new Uni();
				}
				$this->mUniLoaded = true;
			}
			return $this->mUni;
		}
		public function SetUni( $uniid ) {
			global $db;
			global $universities;
			global $users;
			
			$sql = "UPDATE
						`$users`
					SET 
						`user_uniid` = '" . myescape( $uniid ) . "'
					WHERE `user_id` = '" . $this->Id() . "' LIMIT 1;";
			$db->Query( $sql );
			
			return true;
		}
		public function CountSmallNews() {
			return $this->mNumSmallNews;
		}
		public function CountImages() {
			return $this->mNumImages;
		}
        public function CountPolls() {
            return $this->mNumPolls; 
        }
		public function GetFullContributions() {
			global $comments;
			global $users;
			global $db;
			
			$sql = "SELECT 
						COUNT(*) AS contribs 
					FROM 
						`$comments` 
					WHERE 
						`comment_userid`='" . $this->mId . "';";
			$res = $db->Query( $sql );
			$sqlcontribs = $res->FetchArray( $sql );
			$this->mFullContributions = $sqlcontribs[ "contribs" ];
		}
		public function AnsweredQuestions() {
            if ( $this->mAnsweredQuestions === false ) {
                $this->GetUnansweredQuestion();
            }
			return $this->mAnsweredQuestions;
		}
        public function UnansweredQuestions() {
            if ( $this->mUnansweredQuestions === false ) {
                $this->GetUnansweredQuestion();
            }
            return $this->mUnansweredQuestions;
        }
		public function GetUnansweredQuestion() {
			global $questions;
			global $profileanswers;
			global $db;
			
			$sql = "SELECT 
						*
					FROM
						`$questions`
					WHERE
						`profileq_delid`='0';";
						
			$res = $db->Query( $sql );
			
			if ( !$res->Results() ) {
				return false;
			}

            $allquestions = array();
			while ( $row = $res->FetchArray() ) {
				$allquestions[ $row[ 'profileq_id' ] ] = $row;
			}
			$sql = "SELECT
						`profile_questionid`
					FROM
						`$profileanswers`
					WHERE
						`profile_userid`='" . $this->mId . "' AND
						`profile_delid`='0';";
						
			$res = $db->Query( $sql );
			$this->mAnsweredQuestions = $res->NumRows();
			while( $row = $res->FetchArray() ) {
				unset( $allquestions[ $row[ 'profile_questionid' ] ] );
			}
			
			if ( count( $allquestions ) > 0 ) {
    			$selection = rand( 0, count( $allquestions ) - 1 );
                $allquestions = array_values( $allquestions );
		        return New Question( $allquestions[ $selection ] );
			}

			return false;
		}
		public function AnswerQuestion( $questionid , $answer ) {
			global $profileanswers;
			global $users;
			global $db;
			
			$questionid = myescape( $questionid );			
			$nowdate = NowDate();
			$ip = UserIp();
			$answer = myescape( $answer );

			$sql = "INSERT INTO `$profileanswers`
						(`profile_userid`, `profile_answer`, `profile_questionid`, `profile_date`, `profile_delid`, `profile_userip`)
					VALUES
						('" . $this->Id() . "', '$answer', '$questionid', '$nowdate', '0', '$ip')
					ON DUPLICATE KEY UPDATE
						`profile_answer` = VALUES(`profile_answer`),
						`profile_date` = VALUES(`profile_date`),
						`profile_delid` = VALUES(`profile_delid`),
						`profile_userip` = VALUES(`profile_userip`);";
						
			$change = $db->Query( $sql );
						
			if ( $change->Impact() ) {
				$sql = "UPDATE
							`$users`
						SET
							`user_lastprofedit`='$nowdate'
						WHERE
							`user_id`='" . $this->mId . "'
						LIMIT 1;";
						
				$db->Query( $sql );
				
				return true;
			}
			else {
				return false;
			}
		}
		public function DeleteAnswer( $questionid ) {
			global $profileanswers;
			global $users;
			global $db;
			
			$questionid = myescape( $questionid );
			
			$sql = "UPDATE `$profileanswers` 
					SET `profile_delid` = '1'
					WHERE `profile_userid` = '" . $this->Id() . "' AND `profile_questionid` = '$questionid'
					LIMIT 1;";
			
			$change = $db->Query( $sql );
			if ( $change->Impact() ) {
				return true;
			}
			return false;
		}
		public function UndoDeleteAnswer( $questionid ) {
			global $profileanswers;
			global $users;
			global $db;
			
			if ( $this->Contributions() <= count( $this->GetAnsweredQuestions() ) ) { // no right to answer a new question
				return false;
			}
			
			$questionid = myescape( $questionid );
			
			$sql = "SELECT * FROM `$profileanswers` 
					WHERE `profile_userid` = '" . $this->Id() . "' AND `profile_questionid` = '$questionid' AND `profile_delid` = '0'
					LIMIT 1;";
			
			$res = $db->Query( $sql );
			if ( $res->Results() ) { // tries to restore an answer that he later answered
				return false;
			}
			
			$sql = "UPDATE `$profileanswers`
					SET `profile_delid` = '0'
					WHERE `profile_userid` = '" . $this->Id() . "' AND `profile_questionid` = '$questionid' AND `profile_delid` = '1'
					LIMIT 1;";
					
			$res = $db->Query( $sql );
			if ( !$res->Impact() ) {
				return false;
			}
			return true;
		}
		public function GetAnsweredQuestions() {
			global $questions;
			global $profileanswers;
			global $db;
			
			if( !$this->mGotAnsweredQuestions ) {
				$sql = "SELECT 
							`profileq_question`,
							`profileq_id`,
							`profile_answer`,
							`profile_date`
						
						FROM
							`$profileanswers` CROSS JOIN `$questions`
                                ON `profile_questionid`=`profileq_id`
						WHERE
							`profile_userid`='" . $this->mId . "' AND
							`profile_delid`='0' AND
							`profileq_delid`='0'
						
						ORDER BY
							`profile_date`
						DESC;";
					
				$res = $db->Query( $sql );
				while ( $sqlquestion = $res->FetchArray() ) {
					$question = New Question( $sqlquestion );
					$this->mQuestions[] = $question;
				}
				$this->mGotAnsweredQuestions = true;
			}
			
			return $this->mQuestions;
		}
		public function NextQuestion() {
			if ( $this->mQIndex == -1 || $this->mQuestions[ $this->mQIndex ] === false ) {
				$this->mQIndex = -1;
				return false;
			}
			$this->mQIndex++;
			return $this->mQuestions[ $this->mQIndex ];
		}
		public function FirstQuestion() {
			$mQIndex = -1;
			return NextQuestion();
		}
		public function MakeBlog( $storyid ) {
			global $users;
			global $db;
			
			$sql = "UPDATE
						`$users`
					SET
						`user_blogid`='$storyid'
					WHERE
						`user_id`='" . $this->mId . "'
					LIMIT 1;";
			$db->Query( $sql );
			$this->mBlog = $storyid;
		}
		public function UpdateLastLogon() {
			global $users;
			global $db;
			
			$nowdate = NowDate();
			
			$nowdate = myescape( $nowdate );
			$uid = myescape( $this->Id() );
			
			$sql = "UPDATE `$users` SET `user_lastlogon`='$nowdate' WHERE `user_id`='$uid' LIMIT 1;";
			
			$change = $db->Query( $sql );
		}
		public function Pageviews() {
			global $pageviews;
			global $db;
			
			return $this->mPageviews;
		}
		public function ArticlesPageviews() {
			global $pageviews;
			global $articles;
			global $revisions;
			global $db;
			
			if( $this->mArticlesPageviews == false ) {
				$sql = "SELECT
							AVG( views ) AS articleviews
							FROM
							(
								SELECT 
										COUNT( * ) AS views
									FROM 
										`$pageviews` , `$articles`, `$revisions`
									WHERE 
										`article_typeid` = '0' AND
										`revision_articleid` = `article_id` AND
										`revision_creatorid` = '" . $this->Id() . "' AND
										`revision_minor` = 'no' AND
										`article_delid` = '0' AND 
										`pageview_itemid` = `article_id` AND 
										`pageview_type` = 'article'
									GROUP BY 
										`article_id`
							) AS pageviews
						;";
						
				$res = $db->Query( $sql );
				$fetched = $res->FetchArray();
				$this->mArticlesPageviews = $fetched[ "articleviews" ];
			}

			return $this->mArticlesPageviews;
		}	
		public function AddPageview() {
			global $users;
			global $db;
			
			$sql = "UPDATE `$users` SET `user_profviews` = `user_profviews` + 1 WHERE `user_id` = '" . $this->Id() . "' LIMIT 1;";
			$change = $db->Query( $sql );
			
			if ( $change->Impact() ) {
				++$this->mPageviews;
			}
			
			return $change->Impact();
		}
		public function Popularity() {
			if ( $this->mPopularity == false ) {
				$toppageviews = Users_TopPageviews();
				if ( $toppageviews == 0 ) { // bordercase, just in case
					$this->mPopularity = 1;
				}
				else {
					$this->mPopularity = $this->Pageviews() / $toppageviews;
				}
			}
			
			return $this->mPopularity;
		}
		public function UserboxStatus() {
			if ( empty( $this->mUserboxStatus ) ) {
				if ( isset( $_COOKIE[ 'cclogin' ] ) ) {
					$cookieinfo = explode( ':' , $_COOKIE[ 'cclogin' ] );
				}
				else {
					$cookieinfo = false;
				}
				$this->mUserboxStatus = empty( $cookieinfo[ 0 ] ) ? "shown" : $cookieinfo[ 0 ];
			}
			return $this->mUserboxStatus;
		}
		public function SetUserboxStatus( $status ) {
			$this->mUserboxStatus = $status;
		}
		public function SetCookie( $doempty = false, $uauth = "" ) {
			global $xc_settings; 
			
			//sets the cookie and returns the raw cookie for js usage
			if ( !$doempty ) {
				$eofw = 2147483646;
				$uid = $this->Id();
				$uauth = $this->Authtoken();
				setcookie( $xc_settings[ 'cookiename' ], "$uid:$uauth" , $eofw, '/', $xc_settings[ 'cookiedomain' ] );
				
				//return $xc_settings[ 'cookiename' ] . "=$uid:$uauth; expires=" . date( "r" , $eofw ) . "; path=/; domain=" . $xc_settings[ 'cookiedomain' ];
			}
			else { // clear
				setcookie( $xc_settings[ 'cookiename' ], '' , time() - 86400, '/', $xc_settings[ 'cookiedomain' ] );
				//return $xc_settings[ 'cookiename' ] . "=; expires=" . date( "r" , time() - 86400 );
			}
		}
		public function Exists() {
			return $this->mId > 0;
		}
		
		public function Frel_type() {
			return $this->mFrel_type;
		}
		public function DeleteFriend( $friend_id ) {
			global $db;
			global $relations;
			
			$userid = $this->Id();
			
			$sql = "DELETE FROM 
						`$relations` 
					WHERE
						`relation_userid` = '$userid' AND `relation_friendid` = '$friend_id'
					LIMIT 1";
			
			$change = $db->Query( $sql );
			return $change->AffectedRows() == 1;
		}
		
		public function AddFriend( $friend_id, $friend_type, $wasfriend = false ) {
			global $db;
			global $user;
			global $relations;
			global $libs;
			
			$libs->Load( 'notify' );

			$nowdate = NowDate();
			$relations = 'merlin_relations';
			$sql = "INSERT IGNORE INTO 
						`$relations` 
					( `relation_id` ,`relation_userid`, `relation_friendid`, `relation_type`, `relation_created` ) VALUES
					( '' , '" . $this->Id() . "', '" . $friend_id . "', '" . $friend_type . "', '$nowdate' );";
			
			$change = $db->Query( $sql );
			if ( !$wasfriend ) {
				Notify_Create( $user->Id() , $friend_id , $friend_id , 128 );
			}
			
			return $change->AffectedRows() == 1;
		}
		
		public function GetFans() {
			global $db;
			global $relations;
			global $users;
			global $images;
			global $friendrel;
            
			$userid = $this->Id();
			
			$sql = "SELECT 
						`relation_userid`, `relation_created`, `user_id` , `user_name`, `user_subdomain`
						`user_lastprofedit`, `user_icon`, `user_rights` , `user_hobbies`,
                        `image_id`, `image_userid`, `frel_type`
					FROM 
						`$relations`
							RIGHT JOIN `$friendrel` ON `frel_id` = `relation_type`
							CROSS JOIN `$users` ON `relation_userid` = `user_id` 
                            LEFT JOIN `$images` ON `user_icon` = `image_id`
					WHERE 
						`relation_friendid` = '$userid'";
						
			$res = $db->Query( $sql );
			
			$user_fans = array();
			while ( $row = $res->FetchArray() ) {
				$user_fans[] = new User( $row );
			}
			
			return $user_fans;
		}	
		public function GetFriends() {
			global $db;
			global $relations;
			global $users;
            global $images;
            global $friendrel;
			
			$userid = $this->Id();
			$sql = "SELECT 
						`relation_friendid`, `relation_created`, `user_id` , `user_name`, `user_subdomain`
						`user_lastprofedit`, `user_icon`, `user_rights` , `user_hobbies`,
                        `image_id`, `image_userid`, `frel_type`
					FROM 
						`$relations` 
							RIGHT JOIN `$friendrel` ON `frel_id` = `relation_type`
							CROSS JOIN `$users` ON `relation_friendid` = `user_id` 
                            LEFT JOIN `$images` ON `user_icon` = `image_id`   
					WHERE 
						`relation_userid` = '$userid'";
						
			$res = $db->Query( $sql );
			
			$user_friends = array();
			while ( $row = $res->FetchArray() ) {
				$user_friends[] = new User( $row );
			}
			
			return $user_friends;
		}
		public function IsFriend( $friendid ) {
			global $db;
			global $relations;
			
			$userid = $this->Id();
			$sql = "SELECT	
						`relation_friendid`
					FROM 
						`$relations`
					WHERE
						`relation_friendid` = '$friendid' AND `relation_userid` = '$userid';";
			$res = $db->Query( $sql );
			if ( $res->NumRows() != 0 ) {
				return true;
			}
			else {
				return false;
			}
		}
		public function GetRelId( $friendid ) {
			global $db;
			global $relations;
			
			$userid = $this->Id();
			$sql = "SELECT
						`relation_type`
					FROM
						`$relations`
					WHERE
						`relation_friendid` = '$friendid' AND `relation_userid` = '$userid';";
			$res = $db->Query( $sql );
			if( $res->NumRows() == 0 ) {
				return false;
			}
			else {
				$fetched = $res->FetchArray();
				return $fetched[ 'relation_type' ];
			}
		}
		public function UserSpace() {
			global $db;
			global $users;
			global $articles;
			global $revisions;
			global $blk;
			
			$sql = "SELECT
						`revision_textid`
					FROM
						`$articles`, `$revisions`
					WHERE
						`article_creatorid` = '" . $this->Id() . "' AND
						`article_typeid` = '2' AND
						`revision_articleid` = `article_id` AND
						`revision_id` = `article_headrevision`
					;";
			
			$res = $db->Query( $sql );
			$fetched = $res->FetchArray();
			
			if ( !is_numeric( $fetched[ 'revision_textid' ] ) ) {
				return '';
			}
			return $blk->Get( $fetched[ 'revision_textid' ] );
		}
		public function UpdateActivity( $weekcomments, $weekuniquecomments ) {
			global $db;
			global $users;			
			
			$sql = "UPDATE
						`$users`
					SET
						`user_weekcomments` = `user_weekcomments` + $weekcomments,
						`user_weekuniquecomments` = `user_weekuniquecomments` + $weekuniquecomments
					WHERE
						`user_id` = '" . $this->Id() . "'
					LIMIT 1;";
			$change = $db->Query( $sql );
			
			return $change->AffectedRows() == 1;
		}
		public function UpdateInChat() {
			global $db;
			global $users;
			
			$sql = "UPDATE
						`$users`
					SET
						`user_inchat` = NOW()
					WHERE
						`user_id` = '" . $this->Id() . "'
					LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function CountUnreadPms() {
			global $db;
			global $pms;
			
			$sql = "SELECT * FROM `$pms` WHERE `pm_delid` = '0' AND `pm_to` = '" . $this->Id() . "';";
			
			$res = $db->Query( $sql );
			
			return $res->NumRows();
		}
		public function SentPMs() {
			global $pms;
			global $users;
			global $db;
			
			$sql = "SELECT 
							* 
						FROM 
							`$pms` INNER JOIN `$users` ON 
							`user_id` = `pm_to`
						WHERE 
							`pm_from` = '" . $this->Id() . "'
						ORDER BY 
							`pm_created` DESC
					;";
					
			$res = $db->Query( $sql );
			
			$ret = array();
			while ( $row = $res->FetchArray() ) {
				$pm = New Pm( $row );
				$ret[] = $pm;
			}
			
			return $ret;
		}
		public function ReceivedPMs() {
			global $pms;
			global $users;
			global $db;
			
			$sql = "SELECT 
							* 
						FROM 
							`$pms` INNER JOIN `$users` ON 
							`user_id` = `pm_from`
						WHERE 
							`pm_to` = '" . $this->Id() . "'
						ORDER BY 
							`pm_created` DESC
					;";
					
			$res = $db->Query( $sql );
			
			$ret = array();
			while( $row = $res->FetchArray() ) {
				$pm = New PM( $row );
				$ret[] = $pm;
			}
			
			return $ret;
		}
		public function NumComments() {
			return $this->mNumComments;
		}
		public function ArticlesNum() {
			global $db;
			global $articles;
			
			if ( !$this->mArticlesNum ) {
				$sql = "SELECT 
							COUNT(*) AS `article_nums`
						FROM
							`$articles`
						WHERE 
							`article_creatorid`='" . $this->Id() . "' 
							AND `article_typeid`='0'
							AND `article_delid`='0'
						GROUP BY `article_creatorid`
						LIMIT 1;";
				$res = $db->Query( $sql );
				$row = $res->FetchArray();
				$this->mArticlesNum = $row[ "article_nums" ];
			}
			return $this->mArticlesNum;
		}
		public function SendPM( $receiver, $text ) {
			global $db;
			global $pms;
			
			
			$sqlarray = array(
				'pm_id' => '',
				'pm_from' => $this->Id(),
				'pm_to' => $receiver->Id(),
				'pm_created' => NowDate(),
				'pm_userip' => UserIp(),
				'pm_text' => $text,
				'pm_delid' => 0
			);
			
			$db->Insert( $sqlarray, $pms );
		}
		public function MessagesRead() {
			global $db;
			global $pms;
			
			$sql = "UPDATE `$pms` SET `pm_delid`='1' WHERE `pm_to`='" . $this->Id() . "' AND `pm_delid`='0';";
			
			$db->Query( $sql );
		}
		public function CommentAdded() {
			global $db;
			global $users;
			
			++$this->mNumComments;
			
			$sql = "UPDATE
						`$users`
					SET
						`user_numcomments` = `user_numcomments` + 1
					WHERE
						`user_id` = '" . $this->Id() . "'
					LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function CommentKilled() {
			global $db;
			global $users;
			
			--$this->mNumComments;
			
			$sql = "UPDATE
						`$users`
					SET
						`user_numcomments` = `user_numcomments` - 1
					WHERE
						`user_id` = '" . $this->Id() . "'
					LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function Authtoken() {
			return $this->mAuthtoken;
		}
		public function IsOnline() {
			$lastactive = strtotime( $this->mLastActive ) + 5 * 60;
			
			if ( $lastactive > strtotime( "now" ) ) {
				return true;
			}
			else {
				return false;
			}
		}
		public function Weight() {
			return $this->mWeight;
		}
		public function Height() {
			return $this->mHeight;
		}
		public function EyeColor() {
			return $this->mEyeColor;
		}
		public function HairColor() {
			return $this->mHairColor;
		}
        public function SetProfileColor( $color ) {
            global $db;
            global $users;
            
            w_assert( is_int( $color ) );
            
            return $db->Query(
                "UPDATE
                    `$users`
                SET
                    `user_profilecolor` = " . $color . "
                WHERE
                    `user_id` = " . $this->Id() . "
                LIMIT 1"
            )->Impact();
        }
        public function ProfileColor() {
            return $this->mProfileColor;
        }
		public function User( $construct ) {
			global $db;
			global $users;
			global $images;
			global $water;
            global $xc_settings;
            
			if ( is_array( $construct ) ) {
				// fetched array
				$fetched_array = $construct;
			}
			else if ( is_numeric( $construct ) ) {
				// by id
				$id = myescape( $construct );
				$sql = "SELECT 
                            *, ( `user_created` " . $xc_settings[ "mysql2phpdate" ] . " ) AS `user_cutedate`
                        FROM 
                            `$users` LEFT JOIN `$images`
                                ON `user_icon` = `image_id`
                        WHERE 
                            `user_id` = '$id' 
                        LIMIT 1;";
			}
			else {
				// by username
                $username = myescape( $construct );
				$sql = "SELECT 
                            *, ( `user_created` " . $xc_settings[ "mysql2phpdate" ] . " ) AS `user_cutedate`
                        FROM 
                            `$users` LEFT JOIN `$images`
                                ON `user_icon` = `image_id`
                        WHERE 
                            `user_name`='$username' 
                        LIMIT 1;";
			}
			if ( !isset( $fetched_array ) ) {
				$res = $db->Query( $sql );
				if ( !$res->Results() ) {
					$water->Notice( 'User constructor failed' , $construct );
					return false;
				}
				$fetched_array = $res->FetchArray();
			}
	
			$this->mId		          	= isset( $fetched_array[ "user_id" ]                ) ? $fetched_array[ "user_id" ]               : 0;
			$this->mRights		      	= isset( $fetched_array[ "user_rights" ]            ) ? $fetched_array[ "user_rights" ]           : 0;
            if ( isset( $fetched_array[ 'image_id' ] ) ) {
                $this->mIcon = New Image( $fetched_array );
            }
            if ( isset( $fetched_array[ 'frel_type' ] ) ) {
            	$this->mFrel_type = $fetched_array[ 'frel_type' ];
            }
			$this->mPlace		      	= isset( $fetched_array[ "user_place" ]             ) ? $fetched_array[ "user_place" ]        		: 0;
			$this->mUniid				= isset( $fetched_array[ "user_uniid" ] 			) ? $fetched_array[ "user_uniid" ]				: 0;
			$this->mBlog		      	= isset( $fetched_array[ "user_blogid" ]            ) ? $fetched_array[ "user_blogid" ]       		: 0;
			$this->mTemplate	      	= isset( $fetched_array[ "user_templateid" ]        ) ? $fetched_array[ "user_templateid" ]     	: 0;
			$this->mICQ		          	= isset( $fetched_array[ "user_icq" ]               ) ? $fetched_array[ "user_icq" ]            	: '0';
			$this->mUsername	      	= isset( $fetched_array[ "user_name" ]              ) ? $fetched_array[ "user_name" ]           	: '';
			$this->mPassword	      	= isset( $fetched_array[ "user_password" ]          ) ? $fetched_array[ "user_password" ]        	: '';
			$this->mSubdomain	      	= isset( $fetched_array[ "user_subdomain" ]         ) ? $fetched_array[ "user_subdomain" ]          : '';
			$this->mSignature	      	= isset( $fetched_array[ "user_signature" ]         ) ? $fetched_array[ "user_signature" ]        	: '';
			$this->mEmail		      	= isset( $fetched_array[ "user_email" ]             ) ? $fetched_array[ "user_email" ]            	: '';
			$this->mMSN		          	= isset( $fetched_array[ "user_msn" ]               ) ? $fetched_array[ "user_msn" ]              	: '';
			$this->mSkype				= isset( $fetched_array[ "user_skype" ]				) ? $fetched_array[ "user_skype" ]			  	: '';
			$this->mYIM		          	= isset( $fetched_array[ "user_yim" ]               ) ? $fetched_array[ "user_yim" ]              	: '';
			$this->mAIM		          	= isset( $fetched_array[ "user_aim" ]               ) ? $fetched_array[ "user_aim" ]              	: '';
			$this->mGTalk		      	= isset( $fetched_array[ "user_gtalk" ]             ) ? $fetched_array[ "user_gtalk" ]            	: '';
			$this->mHobbies		      	= false;
			$this->mSubtitle	      	= isset( $fetched_array[ "user_subtitle" ]          ) ? $fetched_array[ "user_subtitle" ]         	: '';
			$this->mLastLogon	      	= isset( $fetched_array[ "user_lastlogon" ]         ) ? $fetched_array[ "user_lastlogon" ]        	: '0000-00-00 00:00:00';

            $this->mCreated		      	= isset( $fetched_array[ "user_cutedate" ]           ) ? $fetched_array[ "user_cutedate" ]          	: '0000-00-00 00:00:00';
			$this->mDOB		         	= isset( $fetched_array[ "user_dob" ]               ) ? $fetched_array[ "user_dob" ]              	: '0000-00-00 00:00:00';
			$this->mLPE		          	= isset( $fetched_array[ "user_lastprofedit" ]      ) ? $fetched_array[ "user_lastprofedit" ]     	: '0000-00-00 00:00:00';
			$this->mLastActive        	= isset( $fetched_array[ "user_lastactive" ]        ) ? $fetched_array[ "user_lastactive" ]       	: '0000-00-00';
			$this->mRegisterHost      	= isset( $fetched_array[ "user_registerhost" ]      ) ? $fetched_array[ "user_registerhost" ]     	: '0.0.0.0';
			$this->mGender		      	= isset( $fetched_array[ "user_gender" ]            ) ? $fetched_array[ "user_gender" ]        		: 'male';
			$this->mNumComments			= isset( $fetched_array[ "user_numcomments" ] 		) ? $fetched_array[ "user_numcomments" ]		: 0;
			$this->mAuthtoken			= isseT( $fetched_array[ "user_authtoken" ] 		) ? $fetched_array[ "user_authtoken" ]			: "";
			//$this->mLowRes		      	= isset( $fetched_array[ "user_lowres" ]            ) && $fetched_array[ "user_lowres" ]            == "yes";
			$this->mLocked		      	= isset( $fetched_array[ "user_locked" ]            ) ? ( $fetched_array[ "user_locked" ]            == "yes" ) : false;
			$this->mShoutboxActivated 	= isset( $fetched_array[ "user_shoutboxactivated" ] ) ? ( $fetched_array[ "user_shoutboxactivated" ] == "yes" ) : false;
			$this->mHeight				= isset( $fetched_array[ "user_height" ] 			) ? $fetched_array[ "user_height" ]				: "";
			$this->mWeight				= isset( $fetched_array[ "user_weight" ]			) ? $fetched_array[ "user_weight" ]				: "";
			$this->mEyeColor			= isset( $fetched_array[ "user_eyecolor" ]			) ? $fetched_array[ "user_eyecolor" ]			: "";
			$this->mHairColor			= isset( $fetched_array[ "user_haircolor" ]			) ? $fetched_array[ "user_haircolor" ]			: "";
            $this->mProfileColor        = isset( $fetched_array[ "user_profilecolor" ]      ) ? $fetched_array[ "user_profilecolor" ]       : Color_Encode( 255, 255, 255 );
			$this->mNumSmallNews		= isset( $fetched_array[ "user_numsmallnews" ]		) ? $fetched_array[ "user_numsmallnews" ]		: 0;
			$this->mPageviews		  	= isset( $fetched_array[ "user_profviews" ]			) ? $fetched_array[ "user_profviews" ]			: 0;
			$this->mNumImages			= isset( $fetched_array[ "user_numimages" ]			) ? $fetched_array[ "user_numimages" ]			: 0;
            $this->mNumPolls            = isset( $fetched_array[ "user_numpolls" ]          ) ? $fetched_array[ "user_numpolls" ]           : 0;
			
			$this->mArticlesPageviews	= false;
			$this->mPopularity 		  	= false;
			$this->mContribs		 	= isset( $fetched_array[ "user_contribs" ] ) ? $fetched_array[ "user_contribs" ] : 0;
			$this->mPositionPoints = intval( $this->mContribs * 0.5 );
			$this->mArticlesNum			= false;
			$this->mGotAnsweredQuestions = false;
			
			ParseSolDate( $this->mDOB , 
						  $this->mDOBy , $this->mDOBm , $this->mDOBd );
			
			ParseDate( $this->mCreated , 
						$this->mCreateYear , $this->mCreateMonth , $this->mCreateDay ,
						$this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond );
				
			$this->mRegisterDate = MakeDate( $this->mCreated );
			
			ParseDate( $this->mLastLogon , 
						$this->mLastYear , $this->mLastMonth , $this->mLastDay ,
						$this->mLastHour , $this->mLastMinute , $this->mLastSecond );
				
			$this->mLastSince = dateDiff( $this->mLastLogon , NowDate() );
			$this->mCreateSince = dateDiff( $this->mCreated , NowDate() );
			$this->mDaysSinceRegister = daysDistance( $this->mCreated );
			$this->mActiveSince = dateDiff( $this->mLastActive , NowDate() );
			$this->mLogonDate = MakeDate( $this->mLastLogon );
            $this->mAnsweredQuestions = false;
            $this->mUnansweredQuestions = false;

		}
	}
	
	function CheckOnline( $userid ) {
		global $db;
		global $users;
		global $water;
		
		$userid = myescape( $userid );
		$query = "SELECT `user_lastactive` + INTERVAL 5 MINUTE > NOW() - INTERVAL 3 HOUR AS isonline FROM `$users` WHERE `user_id` = '$userid';";
		$sqlr = $db->Query( $query );
		$num_rows = $sqlr->NumRows();
		if ( $num_rows == 1 ) {
			$row = $sqlr->FetchArray();
			if ( $row[ "isonline" ] == 1 ) {
				return true;
			}
			else {
				return false; 
			}
		}
		else {
			$water->Notice( "CheckOnline SQL query returned not one entry" );
		}
	}
	
	function AnsweredQuestions( $userid ) {
		global $db;
		global $profileanswers; 
		
		$userid = myescape( $userid );
		$query = "SELECT COUNT(*) AS qnum FROM `$profileanswers` WHERE `profile_userid`='$userid';";
		$sqlr = $db->Query( $query );
		$questions = $sqlr->FetchArray();
		$num = $questions[ "qnum" ];
		return $num;
	}
	
	function RetrieveUserBlog( $id ) {
		global $db;
		global $articles;
		global $revisions;
		global $bulk;
		
		$id = myescape( $id );
		
		// TODO: DO **NOT** EVER join with BULK! --dionyziz.
		
		$sql = "SELECT 
					`bulk_text`
				FROM
					`$articles`, `$revisions`, `$bulk`
				WHERE
					`article_id` = '$id' AND
					`revision_articleid` = `article_id` AND
					`revision_id` = `article_headrevision` AND
					`bulk_id` = `revision_textid`
				LIMIT 1;";
					
		$res = $db->Query( $sql );
		if ( $res->Results() ) {
			$sqlarticle = $res->FetchArray();
			$thisarticle = New Article( $sqlarticle );
			return $thisarticle;
		}
	}
	
	function IPlist( $id ) {
		global $db;
		global $comments;
		
		$ips = array();
		
		$id = myescape( $id );
		$sqls = "SELECT `comment_userip` FROM `$comments` WHERE `comment_userid`='$id' GROUP BY `comment_userip`;";
		$sqlr = $db->Query( $sqls );
		$num_rows = $sqlr->NumRows();
		for ( $i = 0; $i < $num_rows; $i++ ) {
			$sqlhost = $sqlr->FetchArray();
			$ips[] = $sqlhost[ "comment_userip" ];
		}
		return $ips;
	}
	
	// resolve technical relationships based on comment IP addresses
	function TecRelative( $id ) {
		global $db;
		global $comments;
		global $users;
		
		$id = myescape( $id );
		$sql = "SELECT 
					`$users`.`name`,
					`$users`.`id`
				FROM
					( `$comments` AS mine CROSS JOIN `$comments` AS yours 
						ON ( mine.`comment_userip` = yours.`comment_userip` AND 
							 mine.`comment_id` <> yours.`comment_id` AND
							 mine.`comment_userid` <> yours.`comment_userid` ) )
					CROSS JOIN `$users`
						ON yours.`comment_userid` = `user_id`
				WHERE
					mine.`comment_userid`='" . $id . "'
				GROUP BY
					`user_id`;";
		$sqlr = $db->Query( $sql );
		$num_rows = $sqlr->NumRows();
		for( $i = 0; $i < $num_rows; $i++ ) {
			$fetched_array = $sqlr->FetchArray();
			$relative = New User( $fetched_array );
			$allrelatives[] = $relative;
		}
		return $allrelatives;
	}
	
	function Users_TopPageviews() {
		global $users;
		global $pageviews;
		global $db;
		
		$sql = "SELECT `user_profviews` FROM `$users` ORDER BY `user_profviews` DESC LIMIT 1;";
		
		$res = $db->Query( $sql );
		$row = $res->FetchArray();
		
		return $row[ 'user_profviews' ];
	}
    
    // TODO: move into element
	function RankToText( $rank ) {
		if ( $rank < 10 ) {
			return "Ύπαρξη"; // 0
        }
		if ( $rank >= 50 ) {
			return "Δημοσιογράφος"; // 40
        }
		if ( $rank >= 40 ) {
			return "Μέλος Ομάδας Δημοσίων Σχέσεων"; // 40
        }
		if ( $rank >= 30 ) {
			return "Δημοσιογράφος"; // 30
        }
		if ( $rank >= 20 ) {
			return "Διαχειριστής"; // 20
        }
        return "Χρήστης"; // 10
	}	
	
	function Search_User( $q ) {
		global $db;
		global $users;
		
		$q = myescape( $q );
		$sql = "SELECT * FROM `$users` WHERE `user_name` LIKE '%$q%' LIMIT 1;";
		
		$res = $db->Query( $sql );
		return New User( $res->FetchArray() );
	}
?>
