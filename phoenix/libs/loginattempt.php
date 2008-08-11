<?php
    class LoginAttempt extends Satori {
        protected $mDbTableAlias = 'loginattempts';

        public function LoadDefaults() {
            $this->Created = NowDate();
            $this->Ip = UserIp();
            $this->Success = 'no';
        }
    }
?>
