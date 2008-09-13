<?php
    /*
        Developer:Pagio
    */
    
    class BannedIpFinder extends Finder {
        protected $mModel = 'BannedIp';
        
        public function FindByIp( $ip ) {
            $prototype = new BannedIp();
            $prototype->ip = $ip;
            
            $res = $this->FindByPrototype( $prototype );
            return $res;
        }
    }
    
    class BannedIp extends Satori {
        protected $mDbTableAlias = 'bannedips';

    }
?>
