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
            global $libs;
            
            $libs->Load( 'user/user' );
            
            $sql = $this->mDb->Prepare(
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
            
            if ( count( $users ) == 0 ) {
                return false;
            }            
            
            $userids = array();
            foreach ( $users as $banned ) {
                $userids[] = $banned->Userid;
            }

            $query = $this->mDb->Prepare(
                'SELECT * 
                FROM :users
                WHERE `user_id` IN :userids
                ;'
            );
            $query->BindTable( 'users' );
            $query->Bind( 'userids', $userids );
            $res = $query->Execute();
            
            $real_users = array();
            while ( $row = $res->FetchArray() ) {
                $real_users[ $row[ 'user_id' ] ] = new User( $row );
            }
            
            foreach ( $users as $xrhsths ) {
                $xrhsths->CopyUserFrom( $real_users[ $xrhsths->Userid ] );
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
        
        public function __get( $key ) {
            switch ( $key ) {
                case 'Name':
                    return $this->User->Name;
            }
            return parent::__get( $key );
        }
        
        public function CopyUserFrom( $key ) {
            $this->mRelations[ 'User' ]->CopyFrom( $key );
        }
        
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }        
    }
?>
