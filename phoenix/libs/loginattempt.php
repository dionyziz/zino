<?php
    class LoginAttemptFinder extends Finder {
        protected $mModel = 'LoginAttempt';
        
        public function FindByUserName( $username ) {
            $prototype = new LoginAttempt();
            $prototype->Username = $username;
            
            $found = FindByPrototype( $prototype );            
            return $found;
        }
    }

    class LoginAttempt extends Satori {
        protected $mDbTableAlias = 'loginattempts';

        public function LoadDefaults() {
            $this->Created = NowDate();
            $this->Ip = UserIp();
            $this->Success = 'no';
        }
    }
?>
