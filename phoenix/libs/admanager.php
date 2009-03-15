<?php
    class Ad extends Satori {
        protected $mDbTableAlias = 'ads';
        
        protected function LoadDefaults() {
            global $user;
            
            $this->Userid = $user->Id;
        }
    }
?>
