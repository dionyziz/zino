<?php
    class ElementiPhoneUserProfileView extends Element {
        public function Render( tText $subdomain ) {
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            $theuser = $finder->FindBySubdomain( $subdomain );

            if ( !$theuser->Exists() ) {
                return;
            }
            Element( 'user/avatar', $user->Avatar->Id, $user->Id,
                     $user->Avatar->Width, $user->Avatar->Height,
                     $user->Name, 100, 'avatar', '', true, 30, 30 );
            echo $theuser->Name;
        }
    }
?>
