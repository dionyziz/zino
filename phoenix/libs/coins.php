<?php
    class Coins extends Satori {
        protected $mDbTableAlias = 'coins';
        
        public function LoadDefaults() {
            global $user;
            
            $this->Userid = $user->Id;
            $this->Amount = 10;
        }
    }
?>
