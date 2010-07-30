<?php
    class ControllerTunnel {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $channels ) {
            global $settings;

            clude( 'models/db.php' );
            clude( 'models/comet.php' );
    
            $channelids = explode( ',', $channels );
            $tunnel = PushTunnel::Create();
            foreach ( $channelids as $channelid ) {
                PushTunnel::AddChannel( $tunnel[ 'id' ], $channelid );
            }

            include 'views/tunnel/create.php';
        }
        public static function Update( $tunnelid, $tunnelauthtoken ) {
            clude( 'models/db.php' );
            clude( 'models/comet.php' );

            if ( !PushTunnel::Auth( $tunnelauthtoken ) ) {
                die( 'Invalid tunnel authtoken' );
            }

            PushTunnel::Renew( $tunnelid );
        }
        public static function Delete() {
            clude( 'models/db.php' );
            clude( 'models/comet.php' );

            PushChannel::CleanUp();
        }
    }
?>

