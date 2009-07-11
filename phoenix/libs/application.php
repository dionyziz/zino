<?php
    class Application extends Satori {
        protected $mDbTableAlias = 'appliactions';
        
        protected function LoadDefaults() {
            require_once( '/rabbit/helpers/hashstring.php' );
            
            $this->Created = NowDate();
            $this->Token = GenerateRandomHash();
        }
        
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
    }
    class ApplicationFinder extends Finder {
        protected $mModel = 'appliaction';
        
        public function FindById( tInteger $id ) {
            $prototype = New Application;
            $prototype->Id = $id;
            
            return $this->FindByPrototype( $prototype );
        }
    }
?>