<?php
    class ControllerPoll {
        public static function View( $id, $commentpage = 1 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            include 'models/db.php';
            include 'models/comment.php';
            include 'models/poll.php';
            include 'models/favourite.php';
            $poll = Poll::Item( $id );
            $poll !== false or die;
            if ( $poll[ 'userdeleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            $commentdata = Comment::FindByPage( TYPE_POLL, $id, $commentpage );
            $numpages = $commentdata[ 0 ];
            $comments = $commentdata[ 1 ];
            $countcomments = $poll[ 'numcomments' ];
            $favourites = Favourite::Listing( TYPE_POLL, $id );
            $options = $poll[ 'options' ];
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $myvote = PollVote::Item( $id, $_SESSION[ 'user' ][ 'id' ] );
                if ( !$myvote ) {
                    unset( $myvote );
                }
            }
            include 'views/poll/view.php';
        }
        public static function Listing() {
            include 'models/db.php';
            include 'models/poll.php';
            $polls = Poll::ListRecent();
            include 'views/poll/listing.php';
        }
        public static function Create() {
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
