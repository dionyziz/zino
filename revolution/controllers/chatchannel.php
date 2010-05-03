<?php
    class ControllerChatchannel {
        public static function Listing() {
        }
        public static function Create( $userid ) {
            include_fast( 'models/db.php' );
            isset( $_SESSION[ 'userid' ] ) or die( 'You must be logged in to start a private chat' );
            include_fast( 'models/chat.php' );
            $channel = array(
                'id' => Chat::Create( $_SESSION[ 'userid' ], $userid )
            );
            $participants = array(
                array( 'id' => $_SESSION[ 'userid' ], $userid )
            );
            include 'views/chatchannel/create.php';
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
