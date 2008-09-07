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
            $libs->Load( 'loginattempt' );
            
            $userFinder = new UserFinder();
            $banneduser = $userFinder->FindByName( $user_name );
            
            if ( !$banneduser ) {//if not existing user
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
            $this->BanIps( $logs, $banneduser );
            $banneduser->rigths=0;
            $banneduser->Save();
            
            return true;
        }
        
        protected function BanIps( $ips, $banneduser ) {
            foreach( $ips as $ip ) {
                $banip = new BannedIp( $ip );
                $banip->userid = $banneduser->id;
                $banip->started = NowDate();
                $banip->expire = NowDate()+20;;
                $banip->Save();
            }
            return;
        }
    }
?>
