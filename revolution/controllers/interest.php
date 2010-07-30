<?php
    class ControllerInterest {
        public static function Listing( $userid ) {
            $userid = ( int )$userid;
            clude( 'models/db.php' );
            clude( 'models/interest.php' );
            $interests = Interest::ListByUser( $userid );
            
            include 'views/interest/listing.php';
        }
        public static function Create( $text, $type ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an interest' );
            clude( 'models/db.php' );
            clude( 'models/interest.php' );

            switch ( $type ) {
                case 'hobbies':
                    $typeid = TAG_HOBBIE;
                    break;
                case 'movies':
                    $typeid = TAG_MOVIE;
                    break;
                case 'books':
                    $typeid = TAG_BOOK;
                    break;
                case 'songs':
                    $typeid = TAG_SONG;
                    break;
                case 'artists':
                    $typeid = TAG_ARTIST;
                    break;
                case 'games':
                    $typeid = TAG_GAME;
                    break;
                case 'shows':
                    $typeid = TAG_SHOW;
                    break;
                case 'unknown':
                default:
                    die( 'unknown tag type' );
            }

            $userid = $_SESSION[ 'user' ][ 'id' ];
            $id = Interest::Create( $userid, $text, $typeid );

            include 'views/interest/create.php';
        }
        public static function Delete( $id ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete an interest' );

            clude( 'models/db.php' );
            clude( 'models/interest.php' );

            Interest::Delete( $id );
        }
    }
?>
