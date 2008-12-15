<?php
    class StatusBoxFinder extends Finder {
        protected $mModel = 'StatusBox';
        
        function FindLastByUserId( $userid ) {
            $prototype = new StatusBox();
            $prototype->Userid = $userid;
            $res = $this->FindByPrototype( $prototype, 0, 1, array( 'Created', 'DESC' ) );
            if ( empty( $res ) || $res[ 0 ]->Message == "" ) {
                return false;
            }
            return $res[ 0 ];
        }
    }

    class StatusBox extends Satori {
        protected $mDbTableAlias = 'statusbox';      
        
        protected function LoadDefaults() {
            global $user;            
            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }  
    }        
?>
