<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash, tBoolean $firsttime ) {
            global $libs;
            global $user;
            
            $libs->Load( 'user/profile' );
            $libs->Load( 'rabbit/helpers/http' );

            $userid = $userid->Get();
            $hash = $hash->Get();
            $firsttime = $firsttime->Get();
            
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
            if ( isset( $_SESSION[ 'destuser_id' ] ) ) { // TODO: maybe change this to a URL?
                $destuser = new User( $_SESSION[ 'destuser_id' ] );
                ob_start();
                Element( 'user/url', $destuser->Id, $destuser->Subdomain );
                return Redirect( ob_get_clean() );
            }
            if ( !$firsttime ) {
                return Redirect( '' );
            }
            else {
                return Redirect( '?p=joined' );
            }
        }
    }
?>
