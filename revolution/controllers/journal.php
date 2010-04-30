<?php
    class ControllerJournal {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            include 'models/db.php';
            include 'models/journal.php';
            $journal = Journal::Item( $id );
            $journal !== false or die;
            if ( $journal[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 3 ) {
                include 'models/comment.php';
                $commentdata = Comment::FindByPage( TYPE_JOURNAL, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $journal[ 'numcomments' ];
            }
            if ( $verbose >= 1 ) {
                $user = $journal[ 'user' ];
            }
            if ( $verbose >= 2 ) {
                include 'models/favourite.php';
                $favourites = Favourite::Listing( TYPE_JOURNAL, $id );
            }
            include 'views/journal/view.php';
        }
        public static function Listing() {
            include 'models/db.php';
            include 'models/journal.php';
            $journals = Journal::ListRecent();
            include 'views/journal/listing.php';
        }
        public static function Create() {
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
