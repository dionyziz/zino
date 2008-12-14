<?php
    class StatusBoxFinder extends Finder {
        protected $mModel = 'StatusBox';
        
        function FindLastByUserId( $_user ) {
            $prototype = new StatusBox();
            $prototype->Userid = $_user->Id;
            $res = $this->FindByPrototype( $prototype, 0, 1, array( 'Created', 'DESC' ) );
            if ( empty( $res )  ) {
                return false;
            }
            return $res[ 0 ];
        }
    }

    class StatusBox extends Satori {
        protected $mDbTableAlias = 'statusbox';      
        
        public function LoadDefaults() {
            global $user;            
            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }  
    }        
?>
