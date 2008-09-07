<?php
    /*
        Developer:Pagio
    */
    
    class Ban extends Satori {
        protected $mDbTableAlias = 'bannedips';
        
        public function BanUser( $user_name ) {
            $userFinder = new UserFinder();
            $user = $userFinder->FindByName( $user_name );
            
            if ( !$user ) {
                return false;
            }
            
            return true;
        }
    }
?>
