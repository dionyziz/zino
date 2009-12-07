<?php
    class ControllerFrontpage extends ControllerZino {
        public function Render( tBoolean $newuser, tBoolean $validated ) {
            global $user;
            global $libs;
            
            $libs->Load( 'notify/notify' );
            $newuser = $newuser->Get();
            $validated = $validated->Get();
            if ( $user->Exists() ) {
                $finder = New NotificationFinder();
                $notifs = $finder->FindByUser( $user, 0, 8 );
                $shownotifications = $notifs->TotalCount() > 0;
            }
            else {
                $shownotifications = false;
            }
            $sequencefinder = New SequenceFinder();
            $sequences = $sequencefinder->FindFrontpage();

            $libs->Load( 'user/profile' );
            $showschool = $user->Profile->Education >= 5 && $user->Profile->Placeid > 0;

            Element( 'developer/frontpage/view',$newuser, $validated, $notifs->ToArray(), $sequences, $showschool );
        }
    }
?>
