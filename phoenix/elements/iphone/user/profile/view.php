<?php
    class ElementiPhoneUserProfileView extends Element {
        public function Render( tText $subdomain ) {
            global $user;

            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            $theuser = $finder->FindBySubdomain( $subdomain );

            if ( !$theuser->Exists() ) {
                return;
            }
            ?><div class="profile"><?php
            Element( 'user/avatar', $user->Avatar->Id, $user->Id,
                     $user->Avatar->Width, $user->Avatar->Height,
                     $user->Name, 100, 'avatar', '', true, 30, 30 );
            ?><h2><?php
            echo $theuser->Name;
            ?></h2>
            </div><?php
        }
    }
?>
