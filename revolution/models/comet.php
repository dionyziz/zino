<?php
    define( 'PUSH_PUBLISH_URL', 'http://zino.gr:500/publish?id=' );
    define( 'PUSH_SUBSCRIPTION_EXPIRY', 4 * 60 ); // seconds 

    class PushTunnel {
        public static function Create() {
            $authtoken = '';
            for ( $i = 0; $i < 7; ++$i ) {
                $authtoken .= dechex( rand( 0, 15 ) );
            }

            db(
                'INSERT INTO pushtunnels
                    (`tunnel_authtoken`, `tunnel_created`, `tunnel_expires`) VALUES
                    (:authtoken        , NOW()           , NOW() + INTERVAL ' . PUSH_SUBSCRIPTION_EXPIRY . ' SECOND )',
                compact( 'authtoken' ) 
            );

            $tunnelid = mysql_insert_id();

            return array(
                'id' => $tunnelid,
                'expires' => PUSH_SUBSCRIPTION_EXPIRY,
                'authtoken' => $authtoken
            );
        }
        public static function AddChannel( $tunnelid, $channelid ) {
            db(
                'INSERT INTO push
                    (`push_tunnelid`, `push_channelid`) VALUES
                    (:tunnelid, :channelid)',
                compact( 'tunnelid', 'channelid' )
            );
        }
        public static function Auth( $tunnelid, $auth ) {
            $res = db(
                'SELECT
                    tunnel_id
                FROM
                    pushtunnels
                WHERE
                    tunnel_id=:tunnelid
                    AND tunnel_authtoken=:auth
                LIMIT 1', compact( 'tunnelid', 'auth' )
            );
            return mysql_num_rows( $res );
        }
        public static function Renew( $tunnelid ) {
            db(
                'UPDATE
                    pushtunnels
                SET
                    tunnel_expires = NOW() + INTERVAL ' . PUSH_SUBSCRIPTION_EXPIRY . ' SECOND
                WHERE
                    tunnel_id = :tunnelid
                LIMIT 1', compact( $tunnelid )
            );
        }
        public static function Publish(
            /* int or array of ints */ $tunnelid,
            $xml
            ) {
            if ( is_array( $tunnelid ) ) {
                foreach ( $tunnelid as $id ) {
                    self::Publish( $id, $xml );
                }
                return;
            }

            $curl = curl_init();

            $data = array( 'body' => $xml );

            $server = PUSH_PUBLISH_URL . $tunnelid;
            curl_setopt( $curl, CURLOPT_URL, $server );
            // curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $curl, CURLOPT_POST, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
            curl_setopt( $curl, CURLOPT_FORBID_REUSE, true );
            curl_setopt( $curl, CURLOPT_FRESH_CONNECT, true );

            $data = curl_exec( $curl );

            curl_close( $curl );
        }
    }
    class PushChannel {
        public static function Publish( $channelid, $xml ) {
            $xml = '<channel id="' . $channelid . '">' . $xml . '</channel>';
            $res = db(
                'SELECT
                    tunnel_id AS tunnelid, tunnel_authtoken AS authtoken
                FROM
                    push CROSS JOIN
                        pushtunnels ON push_tunnelid = tunnel_id
                WHERE
                    push_channelid = :channelid', compact( 'channelid' )
            );
            $tunnelids = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $tunnelids[] = $row[ 'tunnelid' ];
            }
            PushTunnel::Publish( $tunnelids, $xml );
        }
        public static function AddToTunnel( $channelid, $tunnelid ) {
            db(
                'INSERT INTO push
                ( push_tunnelid, push_channelid ) VALUES
                ( :tunnelid, :channelid )',
                compact( 'tunnelid', 'channelid' )
            );
        }
        // TODO: expiration check; call this function somewhere!
        public static function CleanUp() {
            db(
                'DELETE FROM
                    push, pushtunnels
                 WHERE
                    push_tunnelid = tunnel_id
                    AND tunnel_expires < NOW()'
            );
        }
    }
?>
