<?php
    class ControllerUser {
        public static function View( $id = false, $subdomain = false, $verbose = 3, $commentpage = 1 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die( 'Comment page is invalid' );
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            try {
                if ( $verbose >= 2 ) {
                    if ( $id ) {
                        $user = User::ItemDetails( $id );
                    }
                    else if ( $subdomain ) {
                        $user = User::ItemDetailsByName( $subdomain );
                    }
                    else {
                        throw New Exception( 'nor id nor subdomain specified for verbosity level 2' );
                    }
                    $countcomments = $user[ 'numcomments' ];
                    if ( !isset( $_SESSION[ 'user' ] ) || $_SESSION[ 'user' ][ 'id' ] != $user[ 'id' ] ) {
                        // only let myself know my rights
                        unset( $user[ 'rights' ] );
                    }
                }
                else {
                    if ( $id ) {
                        $user = User::Item( $id );
                    }
                    else if ( $subdomain ) {
                        $user = User::ItemByName( $subdomain );
                    }
                    $countcomments = 0; // TODO: remove this line
                }
            }
            catch ( ItemDeletedException $e ) {
                $deleted = true;
                include 'views/user/deleted.php';
                return;
            }
            $user !== false or die( 'Specified user was not found' );
            if ( $verbose >= 3 ) {
                clude( 'models/comment.php' );
                clude( 'models/activity.php' );
                clude( 'models/friend.php' );
                clude( 'models/interest.php' );
                clude( 'models/music/song.php' );

                $commentdata = Comment::ListByPage( TYPE_USERPROFILE, $user[ 'id' ], $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $counts = UserCount::Item( $user[ 'id' ] );
                $activities = Activity::ListByUser( $user[ 'id' ] );
                if ( isset( $user[ 'profile' ][ 'songid' ] ) && $user[ 'profile' ][ 'songid' ] != -1 ) {
                    $song = Song::Item( $user[ 'id' ] );
                }
                else { //TODO: rely only on Song::Item
                    $song = false;
                }
                
                if ( $song === false ) {
                    unset( $song );
                }
                
                $interests = Interest::ListByUser( $user[ 'id' ] );
                
                $friendofuser = false;
                if ( isset( $_SESSION[ 'user' ] ) ) {
                    $friendofuser = ( bool ) ( Friend::Strength( $_SESSION[ 'user' ][ 'id' ], $user[ 'id' ] ) & FRIENDS_A_HAS_B );
                }
            }
            Template( 'user/view', compact( 'user', 'counts', 'friendofuser', 'song', 'activities', 'interests', 'comments' ) );
            // include 'views/user/view.php';
        }
        public static function Listing( $query = '', $showoffline = false ) {
            clude( 'models/db.php' );
            clude( 'models/user.php' );

            $online = User::ListOnline();
            foreach ( $online as $i => $user ) {
                $online[ $i ][ 'state' ] = 'online';
            }

            if ( !$showoffline && !empty( $query ) ) {
                $users = array();
                foreach ( $online as $user ) {
                    $name = $user[ 'name' ];
                    if ( substr( $user[ 'name' ], 0, strlen( $query ) ) == $query ) {
                        $users[] = $user;
                    }
                }
            }
            else if ( $showoffline ) {
                $list = User::ListByNameStart( $query );
                $users = array();
                foreach ( $online as $user ) {
                    if ( isset( $list[ $user[ 'name' ] ] ) ) {
                        $users[] = $user;
                    }
                    unset( $list[ $user[ 'name' ] ] );
                }
                foreach ( $list as $user ) {
                    $user[ 'state' ] = 'offline';
                    $users[] = $user;
                }
            }
            else {
                $users = $online;
            }
            include 'views/user/listing.php';
        }
        public static function Create( $name, $email, $password ) {
            clude( 'models/db.php' );
            clude( 'models/user.php' );

            if ( !ValidEmail( $email ) ) {
                $error = 'invalid email';
                Template( 'user/create', compact( 'error' ) );
                return;
            }
            $error = '';
            try {
                $user = User::Create( $name, $email, $password );
                $data = User::Login( $name, $password );
                $success = $data !== false;
                if ( $success ) {
                    global $settings;
                    $eofw = 2147483646;
                    if ( $data[ 'authtoken' ] == '' ) {
                        $data[ 'authtoken' ] = User::RenewAuthtoken( $data[ 'id' ] );
                    }
                    $cookie = $data[ 'id' ] . ':' . $data[ 'authtoken' ];
                    setcookie( $settings[ 'cookiename' ], $cookie, $eofw, '/', $settings[ 'cookiedomain' ], false, true );
                    $_SESSION[ 'user' ] = $data;
                }
            }
            catch ( Exception $e ) {
                $error = $e->getMessage();
            }

            Template( 'user/create', compact( 'user', 'error' ) );
        }
        public static function Update( $multiargs ) {
            $options = $multiargs;
            var_dump( $options );
            
			if ( !is_array( $options ) || empty( $options ) ) {
				return false;
			}
            
			clude( 'models/db.php' );
			clude( 'models/usersettings.php' );
			clude( 'models/user.php' );
			clude( 'models/status.php' );
            
			if ( !isset( $_SESSION[ 'user' ] ) ) {
				throw new Exception( "You must be logged in" );
			}
            $userid = $_SESSION[ 'user' ][ 'id' ];
            
			$whitelist_profile = array( 'email' => 'profile_email', 'placeid' => 'profile_placeid' , 'dob' => 'profile_dob', 'slogan' => 'profile_slogan','sexualorientation' => 'profile_sexualorientation', 'relationship' =>  'profile_relationship', 'religion' => 'profile_religion', 'politics' => 'profile_politics', 'aboutme' => 'profile_aboutme', 'moodid' => 'profile_moodid', 'eyecolor' => 'profile_eyecolor', 'haircolor' => 'profile_haircolor',  'height' => 'profile_height', 'weight' => 'profile_weight', 'smoker' => 'profile_smoker', 'drinker' => 'profile_drinker', 'favquote' => 'profile_favquote', 'mobile' => 'profile_mobile', 'skype' => 'profile_skype', 'msn' => 'profile_msn', 'gtalk' => 'profile_gtalk', 'yim' => 'profile_yim', 'homepage' => 'profile_homepage', 'firstname' => 'profile_firstname', 'lastname' => 'profile_lastname', 'address' => 'profile_address', 'addressnum' => 'profile_addressnum', 'postcode' => 'profile_postcode', 'area' => 'profile_area', 'songid' => 'profile_songid' );
			$whitelist_user = array_flip( array( 'newpass' , 'oldpass',  'gender', 'status' ) );
			$whitelist_settings = array_flip( array( 'emailnotify' , 'notify' ) );
            
			$profile_options = array();
			$settings_updated = 0;
			foreach ( $options as $key => $val ) { 
				if ( isset ( $whitelist_profile[ $key ] ) ) {
					$profile_options[ $whitelist_profile[ $key ] ] = $val;	
				}
				else if ( isset ( $whitelist_user[ $key ] ) ) {
					if ( $key == 'gender' ) {
						User::SetGender( $userid, $val );
					}
					if ( $key == 'status' ) {
						Status::Create( $userid, $key );
					}
					else if ( $key == 'newpass' ) {
						//change only this and return success or failure
						$success = false;
						if ( !isset( $options[ 'oldpass' ] ) || !isset( $options[ 'newpass' ] ) ) {
							clude( 'views/user/changepassword.php' );
							return;
						}		
						$success = User::SetPassword( $userid, $options[ 'oldpass' ], $options[ 'newpass' ] );
						include 'views/user/changepassword.php';
						return;
					}
				}
				else if ( isset ( $whitelist_settings[ $key ] ) ) {
					$settings_updated++;
				}
			}

			if ( !empty( $profile_options ) ) {
				User::UpdateItemDetails( $profile_options, $userid );
			}
            
			if ( $settings_updated == 2 ) {
		        if ( ( $options[ 'emailnotify' ] !== "yes" AND $options[ 'emailnotify' ] !== "no" ) 
		            OR ( $options[ 'notify' ] !== "yes" AND $options[ 'notify' ] !== "no" )  ) {
		            return false;
		        }
		        
		        $pref = array();
		        $pref[ 'emailprofilecomment'] = $pref[ 'emailphotocomment'] = $pref[ 'emailphototag'] = $pref[ 'emailjournalcomment'] = $pref[ 'emailpollcomment'] = $pref[ 'emailreply'] = $pref[ 'emailfriendaddition'] = $pref[ 'emailfriendjournal'] = $pref[ 'emailfriendpoll'] = $pref[ 'emailfriendphoto'] = $pref[ 'emailfavourite'] = $pref[ 'emailbirthday'] = $emailnotify;
		        $pref[ 'notifyprofilecomment'] = $pref[ 'notifyphotocomment'] = $pref[ 'notifyphototag'] = $pref[ 'notifyjournalcomment'] = $pref[ 'notifypollcomment'] = $pref[ 'notifyreply'] = $pref[ 'notifyfriendaddition'] = $pref[ 'notifyfriendjournal'] = $pref[ 'notifyfriendphoto'] = $pref[ 'notifyfriendpoll'] = $pref[ 'notifyfavourite'] = $pref[ 'notifybirthday'] = $notify;
            	Usersettings::Set( $userid, $pref );
			}
			return;

        }
        public static function Delete( $id ) {
            clude( "models/db.php" );
            clude( "models/user.php" );
            $success = User::VirtualDelete( $id );//user rigths = 0, user deleted = 1
            if ( !$success  ) {
                throw new Exception( "Cant delete this user" );
            }
            //cant post
            //others cant see his profile and data
        }
    }
?>
