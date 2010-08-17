<?php
    class ControllerChatParticipant {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create() { // add person to group chat
        }
        public static function Update( $channelid, $typing ) {
            $typing = ( bool )$typing;
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to access the chat application.' );
            clude( 'models/comet.php' );
            clude( 'models/db.php' );
            $userid = $_SESSION[ 'user' ][ 'id' ];
            $username = $_SESSION[ 'user' ][ 'name' ];
            ob_start();
            Template( 'chatchannel/typing', compact( 'channelid', 'typing', 'username' ) );
            $xml = ob_get_clean();
            echo $xml;
            PushChannel::Publish( 'chat/typing/list/' . $channelid, $xml );
        }
        public static function Delete() { // remove person from group chat
        }
    }
?>
