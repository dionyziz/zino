<?php
    class User {
        public static function Login( $username, $password ) {
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name,
                    `user_authtoken` AS authtoken, `user_gender` AS gender
                FROM
                    `users`
                WHERE
                    `user_name` = :username
                    AND `user_password` = MD5( :password ) LIMIT 1',
                compact( 'username', 'password' )
            );
            if ( mysql_num_rows( $res ) ) {
                $row = mysql_fetch_array( $res );
                $row[ 'user_id' ] = ( int )$row[ 'user_id' ];
                return $row;
            }
            return false;
        }
        public static function Item( $id ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS username, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_id` = :id
                LIMIT 1;', array( 'id' => $id )
            );
			return mysql_fetch_array( $res );
        }
        public static function ListOnline() {
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name
                FROM
                    `users`
                    CROSS JOIN `lastactive` ON
                        `user_id` = `lastactive_userid`
                WHERE
                    `lastactive_updated` > NOW() - INTERVAL 5 MINUTE
                ORDER BY
                    `lastactive_updated` DESC'
            );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[ $row[ 'name' ] ] = $row;
            }
            ksort( $ret );
            $ret = array_values( $ret );
            return $ret;
        }
    }
?>
