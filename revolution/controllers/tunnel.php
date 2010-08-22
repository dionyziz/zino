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
        public static function Update( $tunnelid, $tunnelauthtoken, $addchannelid = '', $removechannelid = '' ) {
            clude( 'models/db.php' );
            clude( 'models/comet.php' );

            if ( !PushTunnel::Auth( $tunnelid, $tunnelauthtoken ) ) {
                throw New Exception( 'Invalid tunnel authtoken' );
            }

            PushTunnel::Renew( $tunnelid ); // make sure it doesn't expire
            
            // apply channel changes if necessary
            if ( $addchannelid != '' ) {
                PushTunnel::AddChannel( $tunnelid, $addchannelid );
            }
            else if ( $removechannelid != '' ) {
                PushTunnel::RemoveChannel( $tunnelid, $removechannelid );
            }
        }
        public static function Delete() { // called by cronjob
            clude( 'models/db.php' );
            clude( 'models/comet.php' );

            PushChannel::CleanUp();
        }
    }
?>

