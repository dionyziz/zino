<?php
    class DublicateAccount {
            public function getDublicateAccountsByUserName( $username ) {
                global $db;
                global $libs;
                
                $libs->Load( 'user/user' );
                
                $query = $db->Prepare( "
                    SELECT *
                    FROM `loginattempts`
                    WHERE `login_username` != 'pagio91'
                    AND `login_ip`
                    IN (
                        SELECT `login_ip`
                        FROM `loginattempts`
                        WHERE `login_username` = 'pagio91'
                    );
                ");
                $query->BindTable( 'loginattempts' );
                $query->Bind( 'username', 'pagio91' );
                $res = $query->Execute();
                
                $dubs = array();
                while ( $row = $res->FetchArray() ) {
                    $dubs[] = new User( $row[ 'id'] );
                }
                
                return $dubs;
            } 
    }
?>
