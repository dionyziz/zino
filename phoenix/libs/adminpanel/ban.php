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
            
            $userFinder = new UserFinder();
            $b_user = $userFinder->FindByName( $user_name );
            
            if ( !$b_user ) {//if not existing user
                return false;
            }
            
            //trace relevant ips from login attempts
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
            
            //ban this ips and ban user with this username
            $this->BanIps( $logs, $b_user );
            
            $b_user->rights=0;
            $b_user->Save();
            
            $banneduser = new BannedUser();
            $banneduser->userid = $b_user->id;
            $banneduser->started = NowDate();
            $banneduser->expire = NowDate()+20;//<-fix this
            $banneduser->delalbum = 0;
            $banneduser->Save();

            return true;
        }
        
        protected function BanIps( $ips, $b_user ) {
            foreach( $ips as $ip ) {
                $banip = new BannedIp();
                $banip->ip = $ip;
                $banip->userid = $b_user->id;
                $banip->started = NowDate();
                $banip->expire = NowDate()+20;;//<-fix this
                $banip->Save();
            }
            return;
        }
    }
?>
