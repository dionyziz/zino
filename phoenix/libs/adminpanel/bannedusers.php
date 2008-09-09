<?php
    /*
        Developer:Pagio
    */
    
    class BannedUserFinder extends Finder {
        protected $mModel = 'BannedUser';
        
        public function FindAll( $offset, $limit ) {
            $prototype = new BannedUser();
            $found = FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
            
            return $found;
        }
        
        public function FindByUserID( $userid ) {
            $prototype = new BannedUser();
            $prototype->userid = $userid;
            
            $res = $this->FindByPrototype( $prototype );
            return $res;
        }        
    }   
    
    class BannedUser extends Satori {
        protected $mDbTableAlias = 'bannedusers';    
    }
?>
    
