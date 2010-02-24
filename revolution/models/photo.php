<?php
    function Photo_ListRecent() {
        $res = db(
            'SELECT
                `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_numcomments` AS numcomments
            FROM
                `images`
            WHERE
                `image_delid`=0
            ORDER BY
                id DESC
            LIMIT 100'
        );
        $images = array();
        while ( $row = mysql_fetch_array( $res ) ) {
            $images[] = $row;
        }
        return $images;
    }
    function Photo( $id ) {
        $res = db(
            'SELECT
                `image_id` AS id, `image_userid` AS userid, `image_created` AS created,
                `user_name` AS username, `user_gender` AS gender,
                `image_width` AS w, `image_height` AS h
            FROM
                `images` CROSS JOIN `users`
                    ON `image_userid` = `user_id`
            WHERE
                `image_id` = :id
            LIMIT 1;', array( 'id' => $id )
        );
        if ( mysql_num_rows( $res ) ) {
            return mysql_fetch_array( $res );
        }
        return false;
    }
?>
