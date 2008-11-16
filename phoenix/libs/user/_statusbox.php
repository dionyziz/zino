<?php
    class StatusBoxFinder extends Finder {
        protected $mModel = 'StatusBox';
        
        function FindLastByUser( $userid ) {
            global $db;
            
        
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
