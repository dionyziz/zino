<?php
    class ControllerJournal {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/journal.php' );
            $journal = Journal::Item( $id );
            $journal !== false or die;
            if ( $journal[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 3 ) {
                clude( 'models/comment.php' );
                $commentdata = Comment::ListByPage( TYPE_JOURNAL, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $journal[ 'numcomments' ];
            }
            if ( $verbose >= 1 ) {
                $user = $journal[ 'user' ];
            }
            if ( $verbose >= 2 ) {
                clude( 'models/favourite.php' );
                $favourites = Favourite::ListByTypeAndItem( TYPE_JOURNAL, $id );
            }
            include 'views/journal/view.php';
        }
        public static function Listing( $username = '' ) {
            clude( 'models/db.php' );
            clude( 'models/journal.php' );
            if ( $username != '' ) {
                clude( 'models/user.php' );
                $user = User::ItemByName( $username );
                $journals = Journal::ListByUser( $user[ 'id' ] );
            }
            else {
                $journals = Journal::ListRecent();
            }
            include 'views/journal/listing.php';
        }
        public static function Create( $title, $text ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create a journal' );

            clude( 'models/db.php' );
            clude( 'models/journal.php' );

            $journal = Journal::Create( $_SESSION[ 'user' ][ 'id' ], $title, $text );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );

            include 'views/journal/view.php';
        }
        public static function Update() {
        }
        public static function Delete( $id ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a poll' );

            clude( 'models/db.php' );
            clude( 'models/journal.php' );
            clude( 'models/user.php' );

            $journal = Journal::Item( (int)$id ); 
            $userid = $journal[ 'userid' ];
            if ( $userid != $_SESSION[ 'user' ][ 'id' ] ) {
                die( 'not your journal' );
            }
            Journal::Delete( $id );
        }
    }
?>
