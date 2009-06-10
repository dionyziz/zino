<?php
    class PasswordRequest extends Satori {
        protected $mDbTableAlias = 'passwordrequests';
        
        public function LoadDefaults() {
            $this->Used = false;
            $this->Created = NowDate();
            $this->Hash = GenerateRandomHash();
            $this->Host = UserIp();
        }
    }
?>
