<?php
    define( 'TAG_HOBBIE', 1 );
    define( 'TAG_MOVIE', 2 );
    define( 'TAG_BOOK', 3 );
    define( 'TAG_SONG', 4 );
    define( 'TAG_ARTIST', 5 );
    define( 'TAG_GAME', 6 );
    define( 'TAG_SHOW', 7 );

    class Interest {
        public static function ListByUser( $userid ) {
            return db_array(
                'SELECT
                    `tag_text` AS text, `tag_typeid` AS typeid
                FROM
                    `tags`
                WHERE
                    `tag_userid` = :userid', compact( 'userid' )
            );
        }
        public static function Create( $userid, $text, $typeid ) {
            is_int( $userid ) or die;  
            in_array( $typeid, array( TAG_HOBBIE, TAG_MOVIE, TAG_BOOK, TAG_SONG, TAG_ARTIST, TAG_GAME, TAG_SHOW ) ) or die( "unknown tag typeid" );

            return db( 
                "INSERT INTO `tags` ( `tag_userid`, `tag_text`, `tag_typeid` )
                VALUES ( :userid, :text, :typeid );",
                compact( $userid, $text, $typeid )
            );
        }
        public static function Delete( $id ) {
            return db( "DELETE FROM `tags` WHERE `tag_id` = :id", array( 'id' => $id ) );
        }
    }
?>
