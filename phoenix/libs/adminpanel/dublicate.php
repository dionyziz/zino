<?php
    class DublicateAccount {
            public function getDublicateAccountsByUserName( $username ) {
                global $db;
                
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
                while ( $row = $res->FetchRow() ) {
                    $dubs[] = $row;
                }
                
                return $dubs;
            } 
    }
?>
