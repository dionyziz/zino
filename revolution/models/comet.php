<?php
    define( 'PUSH_SUBSCRIPTION_EXPIRY', 4 * 60 );

    class PushTunnel {
        public static function Create() {
            $authtok = '';
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
                'authtoken' => $authtoke
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
        public static function Publish( $tunnelids, $xml ) {
            // TODO
        }
    }
    class PushChannel {
        public static function Publish( $channelid, $xml ) {
            $res = db(
                'SELECT
                    tunnel_tunnelid AS tunnelid, tunnel_authtoken AS authtoken
                FROM
                    push CROSS JOIN
                        pushtunnels ON push_tunnelid = tunnel_id
                WHERE
                    push_channelid = :channelid', compact( $channelid )
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
                ( :tunnelid, :channelid )'
            );
        }
    }
?>
