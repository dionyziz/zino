<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash ) {
            global $libs;
            global $user;
            
            $libs->Load( 'user/profile' );
            

            $userid = $userid->Get();
            $hash = $hash->Get();
            
            if ( !ValidateEmail( $userid, $hash ) ) {
                ?><p>Η επιβεβαίωση του e-mail σου δεν ήταν δυνατό να πραγματοποιηθεί.<br />
                Παρακαλούμε ξαναδοκίμασε.</p><?php
                return;
            }
            
            $myuser = New $user( $userid );
            $myuser->UpdateLastLogin();
            $myuser->Save();
            $_SESSION[ 's_userid' ] = $myuser->Id;
            $_SESSION[ 's_authtoken' ] = $myuser->Authtoken;
            User_SetCookie( $myuser->Id, $myuser->Authtoken );
            if ( $_SESSION[ 'destuser_id' ] != "" ){
                $destuser = new User( $_SESSION[ 'destuser_id' ] );
                echo $_SESSION[ 'destuser_id' ];
                //return Redirect( Element( 'user/url', $destuser->Id, $destuser->Subdomain ) );
            }
            return Redirect( '?p=joined' );
        }
    }
?>
