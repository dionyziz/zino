<?php
    class ControllerPresence {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $userid, $authtoken ) {
            // called by Presence server
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            clude( 'models/comet.php' );

            $userid = intval( $userid );
            $authtoken = ( string )$authtoken;
            $success = User::UpdateLastActive( $userid, $authtoken );
            $success = true;

            ob_start();
            include 'views/presence/create.php';
            $xml = ob_get_clean();

            echo $xml;

            if ( $success ) {
                PushChannel::Publish( 'presence', $xml );
            }
        }
        public static function Update() {
        }
        public static function Delete( $userid ) {
            // called by Presence server
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            clude( 'models/comet.php' );

            $userid = int( $userid );
            $success = User::UpdateLastActive( $userid );

            ob_start();
            include 'views/presence/delete.php'; 
            $xml = ob_get_clean();

            if ( $success ) {
                PushChannel::Publish( 'presence', $xml );
            }
        }
    }
?>
