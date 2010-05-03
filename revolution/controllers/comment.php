<?php
    class ControllerComment {
        public static function Create( $text, $typeid, $itemid, $parentid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to post a comment' ); // must be logged in to leave comment
            include_fast( 'models/types.php' );
            include_fast( 'models/db.php' );
            switch ( $typeid ) {
                case TYPE_POLL:
                    // include_fast( 'models/poll.php' );
                    break;
                case TYPE_IMAGE:
                    include_fast( 'models/photo.php' );
                    $target = Photo::Item( $itemid );
                    break;
                case TYPE_USERPROFILE:
                    include_fast( 'models/user.php' );
                    // break;
                case TYPE_JOURNAL:
                    // include_fast( 'models/journal.php' );
                    // break;
                case TYPE_SCHOOL:
                    // include_fast( 'models/school.php' );
                    // break;
                default:
                    // comment somewhere unknown
            }            
            $target !== false or die(); // cannot leave a comment on non-existing object
            include_fast( 'models/comment.php' );
            $comment = Comment::Create( $_SESSION[ 'user' ][ 'id' ], $text, $typeid, $itemid, $parentid );
            include_fast( 'models/user.php' );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/comment/view.php';
        }
        public static function View( $commentid ) {
            include_fast( 'models/types.php' );
            include_fast( 'models/db.php' );
            include_fast( 'models/comment.php' );
            $comment = Comment::Item( $commentid );
            $comment[ 'id' ] = $commentid;
            $user = $comment[ 'user' ];
            include 'views/comment/view.php';
        }
    }
?>
