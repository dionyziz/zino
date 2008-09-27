<?php
    /*
        Developer:Pagio
    */
    
    class BannedUserFinder extends Finder {
        protected $mModel = 'BannedUser';
        
        public function FindAll( $offset, $limit ) {
            $prototype = new BannedUser();
            $found = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
            
            return $found;
        }
        
        public function FindByUserId( $userid ) {
            $prototype = new BannedUser();
            $prototype->Userid = $userid;
            
            $res = $this->FindByPrototype( $prototype );
            return $res;
        } 
        
        public function FindActiveByUserId( $userid ) {
            global $db;
            //ToDo!
            return;
        }
    }   
    
    class BannedUser extends Satori {
        protected $mDbTableAlias = 'bannedusers';    
    }
?>
