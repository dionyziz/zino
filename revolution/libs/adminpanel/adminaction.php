<?php
    /* 
        Developer:Pagio
    */

    global $libs;

    $libs->Load( 'poll/poll' );
    $libs->Load( 'image/image' );
    $libs->Load( 'journal/journal' );
    $libs->Load( 'comment' );
    
    class AdminActionFinder extends Finder {
        protected $mModel = 'AdminAction';
            
        public function FindAll( $offset, $limit ) {
            $prototype = New AdminAction();
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
                $users[ $row[ 'user_id' ] ] = New User( $row );
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
                        case TYPE_PHOTO:
                            return 'image';
                        default:
                            return 'unknown type';
                    }
                case 'Action':
                    switch ( $this->Type ) {  
                        case OPERATION_DELETE:
                            return 'delete';
                        case OPERATION_UPDATE:
                            return 'edit';
                        default:
                            return 'unknown operation';
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
        
        public function saveAdminAction( $userid, $userip, $actiontype, $targettype, $targetid ) {        
            $this->Userid = $userid;
            $this->Userip = $userip;
            $this->Targetid = $targetid;            
            $this->Date = NowDate();
            $this->Type = $actiontype;   
            $this->Targettype = $targettype;
            $this->Save();            
        }
    }
?>
