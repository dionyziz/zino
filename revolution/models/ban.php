<?php
	class Ban {
		public static function ItemByUserid( $userid ) {
			$res = db_array(
				'SELECT
					`bannedusers_id` as id, `bannedusers_userid` as userid, `bannedusers_rights` as rights, `bannedusers_started` as started, `bannedusers_expire` as expire, `bannedusers_delalbums` as delalbums,	`bannedusers_reason` as reason, `bannedusers_admin` as admin
				FROM 
					`bannedusers`
				WHERE
					`bannedusers_userid` = :userid
				LIMIT 1', compact( 'userid' ) 
			);
			if ( empty( $res ) ) {
				return false;
			}
			else {
				return array_shift( $res );
			}
		}
		public static function Listing() {
			return db_array(
				'SELECT
					`bannedusers_id` as id, `bannedusers_userid` as userid, `bannedusers_rights` as rights, `bannedusers_started` as started, `bannedusers_expire` as expire, `bannedusers_delalbums` as delalbums,	`bannedusers_reason` as reason, `bannedusers_admin` as admin
				FROM 
					`bannedusers`'
			);
		}
		public static function isBannedUser( $userid ) {
			clude( 'models/date.php' );

			$userid = ( int )$userid;

			$banned = Ban::ItemByUserid( $userid );

			if ( $banned === false ) {
				return false;
			}
			else {
                $diff = strtotime( NowDate() ) - strtotime( $banned[ 'expire' ] );
                
                if ( $diff > 0 ) {
                    //self::Revoke( $banned[ 'userid' ] );
                    return false;
                }
                else {
                    return true;
                }
            }
        }


/*
        public static function Revoke( $userid ) {
            global $libs;
                        
            $libs->Load( 'adminpanel/bannedips' );
            $libs->Load( 'adminpanel/bannedusers' );    
            $libs->Load( 'user/user' );     
            
            $bannedUserFinder = New BanneduserFinder();//delete banneduser
            $bannedUsers = $bannedUserFinder->FindByUserId( $userid );
            
            if ( count( $bannedUsers ) == 1 ) {
                $cur_user = current( $bannedUsers );
                $user_d = New BannedUser( $cur_user->Id );
                $rights = $user_d->Rights;
                $user_d->Delete();                
            }
            else {
                return;
            }
                
            $userFinder = New UserFinder();//restore user rights
            $user = $userFinder->FindById( $userid );
            $user->Rights = $rights;
            $user->Save();   
            
            $ipFinder = New BannedIpFinder();//delete related ips
            $ips = $ipFinder->FindByUserId( $userid );            
            
            foreach ( $ips as $ip ) {
                $ip_d = New BannedIp( $ip->Id );
                $ip_d->Delete();
            }
                          
            return;
        }
    
        public static function isBannedIp( $ip ) {
            global $libs;     
               
            $libs->Load( 'adminpanel/bannedips' );
               
            $ipFinder = New BannedIpFinder();
            $res = $ipFinder->FindActiveByIp( $ip );
               
            if ( empty( $res ) ) {               
                 return false;
            }
            else { 
                return true;
            }
        }
        
        public static function isBannedUser( $userid ) {
            $userFinder = New BannedUserFinder();
            $res = $userFinder->FindByUserId( $userid );
            
            if ( !$res ) {
                return false;
            }
            else {
                $user = current( $res );
                $diff = strtotime( NowDate() ) - strtotime( $user->Expire );
                
                if ( $diff > 0 ) {
                    self::Revoke( $user->Userid );
                    return false;
                }
                else {
                    return true;
                }
            }
        }

        public static function BanIp( $ip, $time_banned = 1728000 ) {//20 days ,time in seconds
            w_assert( is_int( $time_banned ), "Time to be banned is not an integer." );
            w_assert( ( $time_banned > 0 ), "Time to be banned is negative." );
            self::addBannedIps( array( $ip ), -1 , $time_banned );
            return;
        }
    
        
        public static function BanUser( $user_name, $reason, $time_banned = 1728000 ) {//20 days
            w_assert( is_int( $time_banned ), "Time to be banned is not an integer." );
            w_assert( ( $time_banned > 0 ), "Time to be banned is negative." );
        
            global $libs;
            
            $libs->Load( 'user/user' );        
            $libs->Load( 'adminpanel/bannedips' );
            $libs->Load( 'adminpanel/bannedusers' );
            $libs->Load( 'loginattempt' );
            
            
            //check if the user doesn't exist or if he is already banned
            $userFinder = New UserFinder();
            $b_user = $userFinder->FindByName( $user_name );
            
            if ( !$b_user ) {
                return false;
            }
            
            $bannedUserFinder = New BannedUserFinder();
            $exists = $bannedUserFinder->FindByUserId( $b_user->Id );
            
            if ( $exists ) {
                return false;
            }
            //
            
            //trace relevant ips from login attempts            
            $loginAttemptFinder = New LoginAttemptFinder();
            $res = $loginAttemptFinder->FindByUserName( $user_name );
            
            $logs = array();
            foreach ( $res as $logginattempt) {
                $logs[ $logginattempt->Ip ] = $logginattempt->Ip;
            }
            //
            
            //ban this ips and ban user with this username
            self::addBannedIps( $logs, $b_user->Id, $time_banned );            
            self::addBannedUser( $b_user, $reason, $time_banned );

            $b_user->Rights=0;
            $b_user->Save();
            //

            return true;
        }
        
        protected static function addBannedUser( $b_user, $reason, $time_banned ) {
            global $libs;
            global $user;            

            $libs->Load( 'adminpanel/bannedusers' );
            
            $banneduser = New BannedUser();
            $banneduser->Userid = $b_user->Id;
            $banneduser->Rights = $b_user->Rights;
            $banneduser->Started = date( 'Y-m-d H:i:s', time() );
            $banneduser->Expire = date( 'Y-m-d H:i:s', time() + $time_banned );
            $banneduser->Delalbums = 0;            
            $banneduser->Reason = $reason;
            $banneduser->Admin = $user->Name;
            $banneduser->Save();            
            return;        
        }
        
        protected static function addBannedIps( $ips, $user_id, $time_banned ) {
            global $libs;
            
            $libs->Load( 'adminpanel/bannedips' );
        
            $started = date( 'Y-m-d H:i:s', time() );
            $expire = date( 'Y-m-d H:i:s', time() + $time_banned );
            foreach ( $ips as $ip ) {
                $banip = New BannedIp();
                $banip->Ip = $ip;
                $banip->Userid = $user_id;
                $banip->Started = $started;
                $banip->Expire = $expire;
                $banip->Save();
            }
            return;
        }
*/
    }
?>
