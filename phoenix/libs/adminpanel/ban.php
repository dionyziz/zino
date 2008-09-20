<?php
    /*
        Developer:Pagio
    */
    
    class Ban {    
        public function Revoke( $userid ) {
            global $libs;
                        
            $libs->Load( 'adminpanel/bannedips' );
            $libs->Load( 'adminpanel/bannedusers' );    
            $libs->Load( 'user/user' );        
            
            $ipFinder = new BannedIpFinder();//delete related ips
            $ips = $ipFinder->FindByUserId( $userid );            
            
            foreach( $ips as $ip ) {
                $ip_d = new BannedIp( $ip->Id );
                $ip_d->Delete();
            }
            
            $bannedUserFinder = new BanneduserFinder();//delete banneduser
            $bannedUsers = $bannedUserFinder->FindByUserId( $userid );            
            
            if ( !$bannedUsers ) {
                return;
            }
            else if ( count( $bannedUsers ) == 1 ) {
                $cur_user = current( $bannedUsers );
                $user_d = new BannedUser( $cur_user->Id );
                $rights = $user_d->Rights;
                $user_d->Delete();                
            }
                
            $userFinder = new UserFinder();//restore user rights
            $user = $userFinder->FindById( $userid );
            $user->Rights = $rights;
            $user->Save();
              
            return;
        }
    
        public function isBannedIp( $ip ) {
               global $libs;     
               
               $libs->Load( 'adminpanel/bannedips' );
               
               $ipFinder = new BannedIpFinder();
               $res = $ipFinder->FindByIp( $ip );
               
               if ( !$res ) {               
                    return false;
               }
               else { 
                    $ip = current( $res );
                    $diff = strtotime( NowDate() ) - strtotime( $ip->Expire );
                    
                    if ( $diff>0 ) {
                        $this->Revoke( $ip->Userid );
                        return false;
                    }
                    else {
                        return true;
                   }
                }
        }
        
        public function isBannedUser( $userid ) {
            global $libs;
            
            $libs->Load( 'adminpanel/bannedusers' );
            
            $userFinder = new BannedUserFinder();
            $res = $userFinder->FindByUserId( $userid );
            
            if ( !$res ) {
                return false;
            }
            else {
                $user = current( $res );
                $diff = strtotime( NowDate() ) - strtotime( $user->Expire );
                
                if ( $diff > 0 ) {
                    $this->Revoke( $user->Userid );
                    return false;
                }
                else {
                    return true;
                }
            }
        }
        
        public function BanUser( $user_name ) {
            global $libs;
            global $db;
            
            $libs->Load( 'user/user' );        
            $libs->Load( 'adminpanel/bannedips' );
            $libs->Load( 'adminpanel/bannedusers' );
            $libs->Load( 'loginattempt' );
            
            
            //check if the user doesn't exist or is already banned
            $userFinder = new UserFinder();
            $b_user = $userFinder->FindByName( $user_name );
            
            if ( !$b_user ) {
                return false;
            }
            
            $bannedUserFinder = new BannedUserFinder();
            $exists = $bannedUserFinder->FindByUserId( $b_user->Id );
            
            if ( $exists ) {
                return false;
            }
            //
            
            //trace relevant ips from login attempts --implement as Finder
            /*$query = $db->Prepare( 
                'SELECT * FROM :loginattempts
                WHERE login_username=:username 
                GROUP BY  `login_ip`'
            );
            $query->BindTable( 'loginattempts' );
            $query->Bind( 'username' , $user_name );            
            $res = $query->Execute();            */
            
            $loginAttemptFinder = new LoginAttemptFinder();
            $res = $loginAttemptFinder->FindByUserName( $user_name );
            
            $logs = array();
            foreach ( $res as $row) {
                $logs[ $row->Ip ] = $row->Ip;
            }
            //
            
            //ban this ips and ban user with this username
            $this->BanIps( $logs, $b_user );

            $banneduser = new BannedUser();
            $banneduser->Userid = $b_user->Id;
            $banneduser->Rights = $b_user->Rights;
            $banneduser->Started = date( 'Y-m-d H:i:s', time() );
            $banneduser->Expire = date( 'Y-m-d H:i:s', time() + 20*24*60*60 );
            $banneduser->Delalbums = 0;            
            $banneduser->Save();
            
            $b_user->Rights=0;
            $b_user->Save();
            //

            return true;
        }
        
        protected function BanIps( $ips, $b_user ) {
            global $libs;
            
            $libs->Load( 'adminpanel/bannedips' );
        
            $started = date( 'Y-m-d H:i:s', time() );
            $expire = date( 'Y-m-d H:i:s', time() + 20*24*60*60 );
            foreach( $ips as $ip ) {
                $banip = new BannedIp();
                $banip->Ip = $ip;
                $banip->Userid = $b_user->Id;
                $banip->Started = $started;
                $banip->Expire = $expire;
                $banip->Save();
            }
            return;
        }
    }
?>
