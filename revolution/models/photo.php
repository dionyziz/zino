<?php
    class Photo {
        public static function ListRecent( $page = 1 ) {
            --$page;
            $res = db(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_numcomments` AS numcomments
                FROM
                    `images`
                WHERE
                    `image_delid`=0
                ORDER BY
                    id DESC
                LIMIT 100 OFFSET :offset', array( 'offset' => $page * 100 )
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
                    `user_deleted` as userdeleted, `user_name` AS username, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid,
                    `image_width` AS w, `image_height` AS h, `image_numcomments` AS numcomments
                FROM
                    `images` CROSS JOIN `users`
                        ON `image_userid` = `user_id`
                WHERE
                    `image_id` = :id
                LIMIT 1;', array( 'id' => $id )
            );
			$item = array();
			$item = mysql_fetch_array( $res );
            if ( $item === false ) {
                return false;
            }
            $item[ 'userdeleted' ] = ( int )$item[ 'userdeleted' ];
			return $item;
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
