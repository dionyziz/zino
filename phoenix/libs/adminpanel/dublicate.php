<?php
    class DublicateAccount {
            public function getDublicateAccountsByUserName( $username ) {
                global $db;
                global $libs;
                
                $libs->Load( 'user/user' );
                
                $query = $db->Prepare( "
                    SELECT a.user_name FROM :users AS a CROSS JOIN :loginattempts AS b
                    ON b.login_username=a.user_name
                    WHERE b.login_username!='pagio91'
                    AND b.login_ip
                    IN (
                        SELECT `login_ip`
                        FROM `loginattempts`
                        WHERE `login_username` = 'pagio91'
                    )
                    GROUP BY a.user_name
                ");
                $query->BindTable( 'users' );
                $query->BindTable( 'loginattempts' );
                //$query->Bind( 'username', 'pagio91' );
                $res = $query->Execute();
                
                /*$dubs = array();
                while ( $row = $res->FetchArray() ) {
                    $dubs[] =  $row[ 'username' ] ;
                }*/
                
                return $res;
            } 
    }
?>
