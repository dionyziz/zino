<?php
    function Favourite_List( $typeid, $itemid ) {
        return db_array(
            'SELECT
                `user_name` AS username
            FROM
                `favourites` CROSS JOIN `users` 
                    ON `favourite_userid` = `user_id`
            WHERE
                `favourite_typeid`=:typeid
                AND `favourite_itemid`=:itemid',
            compact( 'typeid', 'itemid' )
        );
    }
?>
