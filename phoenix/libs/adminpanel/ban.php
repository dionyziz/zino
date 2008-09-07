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
            $user = $userFinder->FindByName( $user_name );
            
            if ( !$user ) {//if not existing user
                return false;
            }
            
            //trace relevant ips from login attempts
            $query = $db->Prepare( 
                'SELECT * FROM :logintable 
                WHERE login_username=:username 
                GROUP BY  `login_ip`'
            );
            $query->BindTable( 'loginattempts' );
            $query>Bind( 'username' , $user_name );
            
            $res = $query->Execute();
            
            $logs = array();
            while( $row = $res->FetchArray() ) {
                //?><p><?php
                $log = new LoginAttempt( $row );
                $logs[] = $log->ip;
                //?></p><?php
            }
            return $logs;
            
            
            return true;
        }
    }
?>
