<?php
    class ElementApiNotifications extends Element {
        public function Render( tInteger $id, tText $authtoken ) {
            global $libs;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'notify' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindByIdAndAuthtoken( $id->Get(), $authtoken->Get() );
            
            if ( $theuser !== false ) {
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