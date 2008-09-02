<?php
    class AdminActionFinder extends Finder {
        protected $mModel = 'AdminAction';
            
        public function FindAll ( $offset, $limit ) {
            $prototype = new AdminAction();
            $found = $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
            
            $userids=array();
            foreach( $found as $admin ) {
            $userids[] = $admin->userid;            
            }
            
            $query = $this->mDb->Prepare( 
                'SELECT * FROM :users
                WHERE `user_id`  IN :userids'
            );            
            $query->BindTable( 'users' );
            $query->Bind( 'userids',  $userids );
            
            $res = $query->Execute();
            $users = array();
            while( $row = $res->FetchArray() ) {
                $users[ $row[ 'user_id' ] ] = new User( $row );
            }
            
            foreach( $found as $action ) {
                $action->CopyUserFrom( new User( $users[ $action->userid ] ) );
            }
            
            return $found;;
        }
        
        public function Count () {
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT( * ) AS numactions
                FROM
                    :adminactions'
            );
            $query->BindTable( 'adminactions' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            $numactions = $row[ 'numactions' ];
            
            return $numactions;
        }        
    }

    class AdminAction extends Satori {
        protected $mDbTableAlias = 'adminactions';
        
        public function __get( $key ) {
            switch( $key ) {
                case 'name':
                    return $this->User->name;
                case 'target':
                    switch( $this->targettype ) {
                        case 1:
                            return 'comment';
                        case 2:
                            return 'poll';                        
                        case 3:
                            return 'journal';
                        case 4:
                            return 'image';
                    }
                case 'action':
                    switch( $this->type ) {  
                    case 1:
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
        }
        
        public function saveAdminAction ( $userid , $userip , $actiontype , $targettype , $targetid ) {        
            $this->userid = $userid;
            $this->userip = $userip;
            $this->targetid = $targetid;
            
            $today = date( 'Y-m-d H:i:s');
            $this->date = $today;
        
            switch ( $actiontype ) {
                case "delete":
                    $this->type = 1;
                    break;
                case "edit":
                    $this->type = 2;
                    break;
                default:
                    return;
            }
        
            switch ( $targettype ) {
                case "comment":
                    $this->targettype = 1;
                    break;
                case "poll":
                    $this->targettype = 2;
                    break;
                case "journal":
                    $this->targettype = 3;
                    break;
                case "image":
                    $this->targettype = 4;
                    break;
                default:
                    return;
            }
            
            $this->Save();            
            return;        
        }
    }
?>
