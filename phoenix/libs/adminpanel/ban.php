<?php
    /*
        Developer:Pagio
    */
    
    class Ban {
    
        public function BanUser( $user_name ) {
            global $libs;
            
            $libs->Load( 'user/user' );        
            $libs->Load( 'adminpanel/bannedips' );
            
            $userFinder = new UserFinder();
            $user = $userFinder->FindByName( $user_name );
            
            if ( !$user ) {//if not existing user
                return false;
            }
            
            //trace relevant ips from login attempts
            
            
            return true;
        }
    }
?>
