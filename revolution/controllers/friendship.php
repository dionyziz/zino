<?php
    class ControllerJournal {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $friendid ) {
            include 'models/db.php';
            include 'models/friend.php';
            if ( isset( $_SESSION[ 'user' ] ) ) {
                Friend::Create( $_SESSION[ 'user' ][ 'id' ], $friendid, 'FRIENDS_A_HAS_B' );
            }                        
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
