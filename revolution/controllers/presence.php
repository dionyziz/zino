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

            $userid = (int)$userid;
            $authtoken = ( string )$authtoken;
            $info = User::UpdateLastActive( $userid, $authtoken );

            ob_start();
            include 'views/presence/create.php';
            $xml = ob_get_clean();

            echo $xml;

            if ( is_array( $info ) ) {
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
            $info = User::UpdateLastActive( $userid );

            ob_start();
            include 'views/presence/delete.php'; 
            $xml = ob_get_clean();

            if ( is_array( $info ) ) {
                PushChannel::Publish( 'presence', $xml );
            }
        }
    }
?>
