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
        
        public function FindAllActive() {
            global $db;
            global $libs;
            
            $sql = $db->Prepare(
                'SELECT *
                FROM :bannedusers
                WHERE `bannedusers_expire` > NOW( )
                ;'
            );
            $sql->BindTable( 'bannedusers' );
            $res = $sql->Execute();
            
            $users = array();
            while ( $row = $res->FetchArray() ) {
                $users[] = new BannedUser( $row );
            }
            
            return $users;
        }
        
        public function FindByUserId( $userid ) {
            $prototype = new BannedUser();
            $prototype->Userid = $userid;
            
            $res = $this->FindByPrototype( $prototype );
            return $res;
        } 
    }   
    
    class BannedUser extends Satori {
        protected $mDbTableAlias = 'bannedusers';    
    }
?>
