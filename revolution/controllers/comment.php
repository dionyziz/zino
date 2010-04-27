<?php
    class ControllerComment {
        public static function Create( $text, $typeid, $itemid, $parentid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to post a comment' ); // must be logged in to leave comment
            include 'models/types.php';
            include 'models/db.php';
            switch ( $typeid ) {
                case TYPE_POLL:
                    // include 'models/poll.php';
                    break;
                case TYPE_IMAGE:
                    include 'models/photo.php';
                    $target = Photo::Item( $itemid );
                    break;
                case TYPE_USERPROFILE:
                    include 'models/user.php';
                    // break;
                case TYPE_JOURNAL:
                    // include 'models/journal.php';
                    // break;
                case TYPE_SCHOOL:
                    // include 'models/school.php';
                    // break;
                default:
                    // comment somewhere unknown
            }            
            $target !== false or die(); // cannot leave a comment on non-existing object
            include 'models/comment.php';
            $comment = Comment::Create( $_SESSION[ 'user' ][ 'id' ], $text, $typeid, $itemid, $parentid );
            include 'models/user.php';
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/comment/view.php';
        }
        public static function View( $commentid ) {
            include 'models/types.php';
            include 'models/db.php';
            include 'models/comment.php';
            $comment = Comment::Item( $commentid );
            include 'views/comment/view.php';
        }
    }
?>
