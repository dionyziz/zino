<?php
    class StatusBoxFinder extends Finder {
        protected $mModel = 'StatusBox';
        
        function FindLastByUserId( $_user ) {
            $prototype = new StatusBox();
            $prototype->Userid = $_user->Id;
            return $this->FindByPrototype( $prototype, 0, 1, array( 'Created', 'DESC' ) );
        }
    }
    
    class StatusBox extends Satori {
        protected $mDbTableAlias = 'statusbox';
        
        public function ChangeMessage( $userid, $msg ) {
            $status = new StatusBox();
            $status->Message = $msg;
            $status->Userid = $userid;
            $status->Created = NowDate();
            $status->Save();
        }
        
    }        
