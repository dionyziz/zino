<?php
    class Photo {
        public static function ListRecent() {
            $res = db(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_numcomments` AS numcomments,
                    `user_avatarid` AS avatarid
                FROM
                    `images` CROSS JOIN `users`
                        ON `image_userid` = `user_id`
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
        public static function Item( $id ) {
            $res = db(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_name` AS title,
                    `user_name` AS username, `user_gender` AS gender, `user_subdomain` AS subdomain,
                    `image_width` AS w, `image_height` AS h, `image_numcomments` AS numcomments
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
        public static function ListByIds( $ids ) {
            $res = db(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_name` AS title,
                    `user_name` AS username, `user_gender` AS gender, `user_subdomain` AS subdomain,
                    `image_width` AS w, `image_height` AS h, `image_numcomments` AS numcomments
                FROM
                    `images` CROSS JOIN `users`
                        ON `image_userid` = `user_id`
                WHERE
                    `image_id` IN :ids;', array( 'ids' => $ids )
            );
            $images = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $images[] = $row;
            }
            return $images;
        }
    }
?>
