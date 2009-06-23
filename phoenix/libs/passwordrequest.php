<?php
    class PasswordRequest extends Satori {
        protected $mDbTableAlias = 'passwordrequests';
        
        public function LoadDefaults() {
            global $libs;
            
            $libs->Load( 'rabbit/helpers/hashstring' );
            
            $this->Used = false;
            $this->Created = NowDate();
            $this->Hash = GenerateRandomHash();
            $this->Host = UserIp();
        }
    }
?>
