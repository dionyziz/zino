<?php
    function Login( $username, $password ) {
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
            return $row;
        }
        return false;
    }
    class User {
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
    }
?>
