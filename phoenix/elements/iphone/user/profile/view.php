<?php
    class ElementsiPhoneUserProfileView extends Element {
        public function Render( tText $subdomain ) {
            $subdomain = $subdomain->Get();
            $theuser = $finder->FindBySubdomain( $subdomain );

            if ( !$theuser->Exists() ) {
                return;
            }

        }
    }
?>
