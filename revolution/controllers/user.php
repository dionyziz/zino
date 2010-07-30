<?php
    class ControllerUser {
        public static function View( $id = false, $subdomain = false, $name = false, $verbose = 3, $commentpage = 1 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            if ( $verbose >= 3 ) {
                if ( $id ) {
                    $user = User::ItemDetails( $id );
                }
                else if ( $subdomain ) {
                    $user = User::ItemDetailsBySubdomain( $subdomain );
                }
                else if ( $name ) {
                    $user = User::ItemDetailsByName( $name );
                }
                else die;
                $countcomments = $user[ 'numcomments' ];
            }
            else {
                if ( $id ) {
                    $user = User::Item( $id );
                }
                else if ( $subdomain ) {
                    $user = User::ItemBySubdomain( $subdomain );
                }
                else if ( $name ) {
                    $user = User::ItemByName( $name );
                }
                else die;
                $countcomments = 0; // TODO: remove this line
            }
            $user !== false or die;
            if ( $user[ 'userdeleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
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
                $activity = Activity::ListByUser( $user[ 'id' ] );
                $song = Song::Item( $user[ 'id' ] );
                $interests = Interest::ListByUser( $user[ 'id' ] );
                
                if ( $song === false ) {
                    unset( $song );
                }
                $friendofuser = false;
                if ( isset( $_SESSION[ 'user' ] ) ) {
                    $friendofuser = ( bool ) ( Friend::Strength( $_SESSION[ 'user' ][ 'id' ], $user[ 'id' ] ) & FRIENDS_A_HAS_B );
                }
            }
            include 'views/user/view.php';
        }
        public static function Listing() {
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            $users = User::ListOnline();
            include 'views/user/listing.php';
        }
        public static function Create() {
        }
        public static function Update( $multiargs ) {
            $options = $multiargs;

			var_dump( $multiargs );
            
			if ( !is_array( $options ) || empty( $options ) ) {
				return false;
			}
            
			clude( 'models/db.php' );
			clude( 'models/usersettings.php' );
			clude( 'models/user.php' );
            
            $userid = $_SESSION[ 'user' ][ 'id' ];
            
			$whitelist_profile = array( 'email' => 'profile_email', 'placeid' => 'profile_placeid' , 'dob' => 'profile_dob', 'slogan' => 'profile_slogan','sexualorientation' => 'profile_sexualorientation', 'relationship' =>  'profile_relationship', 'religion' => 'profile_religion', 'politics' => 'profile_politics', 'aboutme' => 'profile_aboutme', 'moodid' => 'profile_moodid', 'eyecolor' => 'profile_eyecolor', 'haircolor' => 'profile_haircolor',  'height' => 'profile_height', 'weight' => 'profile_weight', 'smoker' => 'profile_smoker', 'drinker' => 'profile_drinker', 'favquote' => 'profile_favquote', 'mobile' => 'profile_mobile', 'skype' => 'profile_skype', 'msn' => 'profile_msn', 'gtalk' => 'profile_gtalk', 'yim' => 'profile_yim', 'homepage' => 'profile_homepage', 'firstname' => 'profile_firstname', 'lastname' => 'profile_lastname', 'address' => 'profile_address', 'addressnum' => 'profile_addressnum', 'postcode' => 'profile_postcode', 'area' => 'profile_area' );
			$whitelist_user = array_flip( array( 'newpass' , 'oldpass',  'gender' ) );
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
					else if ( $key == 'newpass' || $key == 'oldpass' ) {
						//change only this and return success or failure
						$success = false;
						if ( !isset( $options[ 'oldpass' ] ) || !isset( $options[ 'newpass' ] ) ) {
							clude( 'views/user/changepassword.php' );
							return;
						}						
						$success = User::SetPassword( $userid, $options[ 'oldpass' ], $options[ 'newpass' ] );
						clude( 'views/user/changepassword.php' );
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
        public static function Delete() {
        }
    }
?>
