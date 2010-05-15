<?php
    class ControllerFriendship {
        public static function View( $userid ) {
            clude( 'models/db.php' );
            clude( 'models/friend.php' );

            isset( $_SESSION[ 'user' ] ) or die( 'Please login' );

            $strength = Friend::Strength( $userid );
        }
        public static function Listing() {
        }
        public static function Create( $friendid ) {
            clude( 'models/db.php' );
            clude( 'models/friend.php' );
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $success = Friend::Create( $_SESSION[ 'user' ][ 'id' ], $friendid, 1 );
            }              
            include 'views/friend/create.php';          
        }
        public static function Update() {
        }
        public static function Delete( $friendid ) {
            clude( 'models/db.php' );
            clude( 'models/friend.php' );
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $success = Friend::Delete( $_SESSION[ 'user' ][ 'id' ], $friendid );
            }              
            include 'views/friend/delete.php';    
        }
    }
?>
