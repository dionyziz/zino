<?php
    class ElementiPhoneUserProfileView extends Element {
        public function Render( tText $subdomain ) {
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            $theuser = $finder->FindBySubdomain( $subdomain );

            if ( !$theuser->Exists() ) {
                return;
            }
            echo $theuser->Name;
        }
    }
?>
