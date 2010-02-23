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
                AND `user_password` = MD5( :password ) LIMIT 1' );
        if ( mysql_num_rows( $res ) ) {
            $row = mysql_fetch_array( $res );
            return $row;
        }
        return false;
    }
?>
