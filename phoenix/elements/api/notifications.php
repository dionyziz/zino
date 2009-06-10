<?php
    class ElementApiNotifications extends Element {
        public function Render( tText $username, tText $authtoken ) {
            global $libs;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'notify' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindByNameAndPassword( $username->Get(), $password->Get() );
            
            if ( $user !== false ) {
                $notifinder = New NotificationFinder();
                $notifs = $notifinder->FindByUser( $theuser );
                if ( !empty( $notifs ) ) {
                    foreach ( $notifs as $notif ) {
                        unset( $notifarray );
                        $notifarray[ 'type' ] = Notification_GetField( $notif );
                        $apiarray[] = $notifarray;
                    }
                }
            } else
            {
                $apiarray[ 'error' ][ 'description' ] = "Wrong username or authtoken";
            }
            
        }
    }
?>