<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash, tboolean $firsttime ) {
            global $libs;
            global $user;
            
            $libs->Load( 'user/profile' );
            

            $userid = $userid->Get();
            $hash = $hash->Get();
			$firsttime = $firsttime->Get();
            
            if ( !ValidateEmail( $userid, $hash ) ) {
                ?><p>Η επιβεβαίωση του e-mail σου δεν ήταν δυνατό να πραγματοποιηθεί.<br />
                Παρακαλούμε ξαναδοκίμασε.</p><?php
                return;
            }
            if ( $firsttime ) {
				return Redirect( '?p=joined' );
			}
			
			return Redirect( '?p=a' );
        }
    }
?>
