<?php
    class ControllerJournal {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $friendid ) {
            include 'models/db.php';
            include 'models/friend.php';
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $success = Friend::Create( $_SESSION[ 'user' ][ 'id' ], $friendid, 'FRIENDS_A_HAS_B' );
            }              
            include 'views/friend/create';          
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
