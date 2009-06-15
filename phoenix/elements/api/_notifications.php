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
                        $notifarray[ 'id' ] = $notif->Id;
                        if ( $notif->Typeid == EVENT_COMMENT_CREATED ) {
                            ob_start();
                            Element( 'url' , $notif->Item );
                            $notifarray[ 'url' ] = ob_get_clean();
                        }
                        $apiarray[] = $notifarray;
                    }
                }
            } else
            {
                $apiarray[ 'error' ][ 'description' ] = "Wrong username or authtoken";
            }
            if ( !$xml ) {
                echo w_json_encode( $apiarray );
            }
            else {
                echo 'XML Zino API not yet supported';
            }
        }
    }
?>