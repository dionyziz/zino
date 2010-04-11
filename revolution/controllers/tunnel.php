<?php
    class ControllerTunnel {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $channels ) {
            global $settings;

            include 'models/db.php';
            include 'models/comet.php';
    
            $tunnel = PushTunnel::Create();

            include 'views/tunnel/create.php';
        }
        public static function Update( $tunnelid, $tunnelauthtoken ) {
            include 'models/db.php';
            include 'models/comet.php';

            if ( !PushTunnel::Auth( $tunnelauthtoken ) ) {
                die( 'Invalid tunnel authtoken' );
            }

            PushTunnel::Renew( $tunnelid );
        }
        public static function Delete() {
        }
    }
?>

