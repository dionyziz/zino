<?php
    class BanFinder extends Finder {
        protected $mModel = 'Ban';
        
        public function FindByIp( $ip ) {
            $prototype = New Ban();
            $prototype->Ip = $ip;
            
            return $this->FindByPrototype( $prototype );
        }
    }
    
    class Ban extends Satori {
        protected $mDbTableAlias = 'ipban';
    }
?>
