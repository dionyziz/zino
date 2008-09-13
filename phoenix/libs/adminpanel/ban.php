<?php
    /*
        Developer:Pagio
    */
    
    class Ban {
    
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
            $exists = $bannedUserFinder->FindByUserId( $b_user->id );
            
            if ( !$exists ) {
                return false;
            }
            //
            
            //trace relevant ips from login attempts --implement as Finder
            $query = $db->Prepare( 
                'SELECT * FROM :loginattempts
                WHERE login_username=:username 
                GROUP BY  `login_ip`'
            );
            $query->BindTable( 'loginattempts' );
            $query->Bind( 'username' , $user_name );            
            $res = $query->Execute();            
            
            $logs = array();
            while( $row = $res->FetchArray() ) {
                $log = new LoginAttempt( $row );
                $logs[] = $log->ip;
            }
            //
            
            //ban this ips and ban user with this username
            $this->BanIps( $logs, $b_user );
            
            $b_user->rights=0;
            $b_user->Save();
            
            $banneduser = new BannedUser();
            $banneduser->userid = $b_user->id;
            $banneduser->started = date( 'Y-m-d H:i:s', time() );
            $banneduser->expire = date( 'Y-m-d H:i:s', time() + 20*24*60*60 );
            $banneduser->delalbums = 0;
            $banneduser->Save();
            //

            return true;
        }
        
        protected function BanIps( $ips, $b_user ) {
            $started = date( 'Y-m-d H:i:s', time() );
            $expire = date( 'Y-m-d H:i:s', time() + 20*24*60*60 );
            foreach( $ips as $ip ) {
                $banip = new BannedIp();
                $banip->ip = $ip;
                $banip->userid = $b_user->id;
                $banneduser->started = $started;
                $banneduser->expire = $expire;
                $banip->Save();
            }
            return;
        }
    }
?>
