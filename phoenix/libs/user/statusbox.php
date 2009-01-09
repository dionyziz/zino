<?php
    class StatusBoxFinder extends Finder {
        protected $mModel = 'StatusBox';
        
        function FindLastByUserId( $userid ) {
            $prototype = new StatusBox();
            $prototype->Userid = $userid;
            $res = $this->FindByPrototype( $prototype, 0, 1, array( 'Id', 'DESC' ) );
            if ( empty( $res ) || $res[ 0 ]->Message == "" ) {
                return false;
            }
            return $res[ 0 ];
        }
    }

    class StatusBox extends Satori {
        protected $mDbTableAlias = 'statusbox';      
        
        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Message':
                    $this->mCurrentValues[ 'Message' ] = mb_substr( $value, 0, 256 );
                    return;
            }
            return parent::__set( $key, $value );
        }
        protected function LoadDefaults() {
            global $user;
            
            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }  
    }        
?>
