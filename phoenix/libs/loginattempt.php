<?php

    function LoginAttempt_checkBot( $ip ) {
        global $db;
        
        $query = $db->Prepare(
            "SELECT COUNT(*) AS 'count' 
             FROM :loginattempts
             WHERE `login_ip` = :ip
             AND `login_created` > NOW() - INTERVAL 15 MINUTE
             AND `login_success` = 'no'"
        );
        $query->BindTable( 'loginattempts' );
        $query->Bind( 'ip', $ip );
        $res = $query->Execute();
        
        $row = $res->FetchArray();
        $amount = $row[ 'count' ];
        if ( $amount >= 3 ) {
            return true;
        }
        return false;
    }

    class LoginAttemptFinder extends Finder {
        protected $mModel = 'LoginAttempt';
        
        public function FindByUserName( $username ) {
            $prototype = new LoginAttempt();
            $prototype->Username = $username;
            
            $found = $this->FindByPrototype( $prototype );            
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
