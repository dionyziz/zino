<?php
    /* 
        Developer:Pagio
    */   

    global $libs;

    $libs->Load( 'poll/poll' );
    $libs->Load( 'image/image' );
    $libs->Load( 'journal' );
    
    define( 'OPERATION_UPDATE', 3 );
    define( 'OPERATION_DELETE', 4 );
    
    class AdminActionFinder extends Finder {
        protected $mModel = 'AdminAction';
            
        public function FindAll( $offset, $limit ) {
            $prototype = new AdminAction();
            $found = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
            
            $userids = array();
            foreach ( $found as $admin ) {
                $userids[] = $admin->Userid;            
            }
            
            $query = $this->mDb->Prepare( 
                'SELECT * FROM :users
                WHERE `user_id`  IN :userids'
            );            
            $query->BindTable( 'users' );
            $query->Bind( 'userids',  $userids );
            
            $res = $query->Execute();
            $users = array();
            while ( $row = $res->FetchArray() ) {
                $users[ $row[ 'user_id' ] ] = new User( $row );
            }
            
            foreach ( $found as $action ) {
                $action->CopyUserFrom( $users[ $action->Userid ] );
            }
            
            return $found;
        }
        public function Count() {
            return parent::Count();
        }        
    }

    class AdminAction extends Satori {
        protected $mDbTableAlias = 'adminactions';
        
        public function __get( $key ) {
            switch ( $key ) {
                case 'Name':
                    return $this->User->name;
                case 'Target':
                    switch( $this->Targettype ) {
                        case TYPE_COMMENT:
                            return 'comment';
                        case TYPE_POLL:
                            return 'poll';                        
                        case TYPE_JOURNAL:
                            return 'journal';
                        case TYPE_IMAGE:
                            return 'image';
                        default:
                            return 'not found';
                    }
                case 'Action':
                    switch ( $this->Type ) {  
                        case 1: // TODO: use constants instead?
                            return 'delete';
                        case 2:
                            return 'edit';
                    }
            }
            
            return parent::__get( $key );
        }  
        
        public function CopyUserFrom( $key ) {
            $this->mRelations[ 'User' ]->CopyFrom( $key );
        }
        
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            if ( $this->Exists() ) {
                $this->Item = $this->HasOne( Type_GetClass( $this->Targettype ), 'Targetid' );
            }
        }
        
        public function saveAdminAction( $userid , $userip , $actiontype , $targettype , $targetid ) {        
            $this->Userid = $userid;
            $this->Userip = $userip;
            $this->Targetid = $targetid;
            
            $this->Date = NowDate();
        
            switch ( $actiontype ) {
                case "delete":
                    $this->type = 1;
                    break;
                case "edit":
                    $this->type = 2;
                    break;
            } 

            $this->Targettype = $targettype;
            $this->Save();            
        }
    }
?>
