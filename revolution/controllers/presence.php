<?php
    class ControllerPresence {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create( $userid, $authtoken ) {
            // called by Presence server
            clude( 'models/db.php' );
            clude( 'models/user.php' );

            $userid = intval( $userid );
            $authtoken = (string)$authtoken;
            $success = User::UpdateLastActive( $userid, $authtoken );

            include 'views/presence/create.php';
        }
        public static function Update() {
        }
        public static function Delete( $userid ) {
            // called by Presence server

            $userid = int( $userid );
            $success = User::UpdateLastActive( $userid );
        }
    }
?>

