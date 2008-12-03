<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash ) {
            global $libs;
            
            $libs->Load( 'user/profile' );
            
            $userid = $userid->Get();
            $hash = $hash->Get();
            
            if ( !ValidateEmail( $userid, $hash ) ) {
                ?><p>Η επιβεβαίωση του e-mail σου δεν ήταν δυνατό να πραγματοποιηθεί.<br />
                Παρακαλούμε ξαναδοκίμασε.</p><?php
                return;
            }
            
            return Redirect( '?validated=y' );
        }
    }
?>
