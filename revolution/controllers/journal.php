<?php
    class ControllerJournal {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/journal.php' );
            $journal = Journal::Item( $id );
            if ( $journal === false ) {
                throw New Exception( 'Journal not found' );
            }
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
        public static function Listing( $subdomain = '' ) {
            clude( 'models/db.php' );
            clude( 'models/journal.php' );
            if ( $subdomain != '' ) {
                clude( 'models/user.php' );
                $user = User::ItemByName( $subdomain );
                $journals = Journal::ListByUser( $user[ 'id' ] );
            }
            else {
                $journals = Journal::ListRecent();
            }
            Template( 'journal/listing', compact( 'journals', 'user' ) );
        }
        public static function Create( $title, $text ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create a journal' );

            clude( 'models/db.php' );
            clude( 'models/user.php' );
            clude( 'models/journal.php' );

            $journal = Journal::Create( $_SESSION[ 'user' ][ 'id' ], $title, $text );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );

            ob_start();
            Template( 'journal/view', compact( 'journal', 'user' ) );
            $xml = ob_get_clean();

            clude( 'models/comet.php' );
            PushChannel::Publish( 'journal/list', $xml );

            echo $xml;
        }
        public static function Update( $id, $title = false, $text = false ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to update a journal' );
            
            clude( 'models/db.php' );
            clude( 'models/journal.php' );

            $journal = Journal::Item( (int)$id );
            $user = $journal[ 'user' ];

            if ( $user[ 'id' ] != $_SESSION[ 'user' ][ 'id' ] ) {
                throw New Exception( 'not your journal' );
            }

            $title = $title !== false ? $title : $journal[ 'title' ];
            Journal::Update( $id, $title, $text );
            $journal[ 'title' ] = $title;
            $journal[ 'text' ] = $text;

            Template( 'journal/view', compact( 'journal', 'user' ) );
        }
        public static function Delete( $id ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a journal' );

            clude( 'models/db.php' );
            clude( 'models/journal.php' );
            clude( 'models/user.php' );

            $journal = Journal::Item( (int)$id ); 
            $userid = $journal[ 'userid' ];
            if ( $userid != $_SESSION[ 'user' ][ 'id' ] ) {
                $admin = User::Item( $_SESSION[ 'user' ][ 'id' ] );
                if ( $admin[ 'rights' ] > 30 ) {
                    // admin override
                }
                else {
                    throw New Exception( 'not your journal' );
                }
            }
            
            Journal::Delete( $id );
        }
    }
?>
