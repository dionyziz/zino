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
                    `tag_text`, `tag_typeid`
                WHERE
                    `tag_userid` = :userid', compact( 'userid' )
            );
        }
    }
?>
