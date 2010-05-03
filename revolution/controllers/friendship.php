<?php
    class ControllerFriendship {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $friendid ) {
            include_fast( 'models/db.php' );
            include_fast( 'models/friend.php' );
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $success = Friend::Create( $_SESSION[ 'user' ][ 'id' ], $friendid, 1 );
            }              
            include 'views/friend/create.php';          
        }
        public static function Update() {
        }
        public static function Delete( $friendid ) {
            include_fast( 'models/db.php' );
            include_fast( 'models/friend.php' );
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $success = Friend::Delete( $_SESSION[ 'user' ][ 'id' ], $friendid );
            }              
            include 'views/friend/delete.php';    
        }
    }
?>
