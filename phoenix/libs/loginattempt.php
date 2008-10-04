<?php

    function LoginAttempt_checkBot( $ip ) {
        global $db;
        
        $query = $db->Prepare(
            "SELECT COUNT(*) AS 'count' 
             FROM :loginattempts
             WHERE `login_ip` = :ip
             AND `login_created` > NOW() - INTERVAL 15 MINUTE
             AND `login_success` = 'no'
             ;"
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
    
    function LoginAttempt_checkDuplicate( $ip ) {
        global $db;
        
        $query = $db->Prepare(
            "SELECT `login_username` 
             FROM :loginattempts
             WHERE `login_ip` = :ip
             AND `login_created` > NOW() - INTERVAL 2 DAY
             AND `login_success` = 'yes'
             GROUP BY `login_username`
             ;"
        );
        $query->BindTable( 'loginattempts' );
        $query->Bind( 'ip', $ip );
        $res = $query->Execute();
        
        $users = array();
        while ( $row = $res->FetchArray() ) {
            $users[] = $row[ 'login_username' ];
        }        
        return $users;
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
