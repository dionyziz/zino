<?php
    class ControllerPoll {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            include 'models/db.php';
            include 'models/poll.php';
            include 'models/favourite.php';
            $poll = Poll::Item( $id );
            $poll !== false or die;
            if ( $poll[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 1 ) {
                $user = $poll[ 'user' ];
            }
            if ( $verbose >= 3 ) {
                include 'models/comment.php';
                $commentdata = Comment::FindByPage( TYPE_POLL, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $poll[ 'numcomments' ];
            }
            if ( $verbose >= 2 ) {
                $favourites = Favourite::Listing( TYPE_POLL, $id );
            }
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
