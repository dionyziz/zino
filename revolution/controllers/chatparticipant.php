<?php
    class ControllerChatParticipant {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create() { // add person to group chat
        }
        public static function Update( $channelid, $typing ) {
            // warning: no authentication here
            // anyone can claim they are typing in any channel
            // it remains for the client to validate that
            // the claimed participant is a member of the
            // channel they claim they are typing in
            $typing = ( bool )$typing;
            $channelid = ( int )$channelid;
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to access the chat application.' );
            clude( 'models/comet.php' );
            clude( 'models/chat.php' );
            clude( 'models/db.php' );
            $userid = $_SESSION[ 'user' ][ 'id' ];
            $username = $_SESSION[ 'user' ][ 'name' ];
            ob_start();
            Template( 'chatchannel/typing', compact( 'channelid', 'typing', 'username' ) );
            $xml = ob_get_clean();
            echo $xml;
            $participants = ChatChannel::ParticipantList( $channelid );
            foreach ( $participants as $participants ) {
                PushChannel::Publish( 'chat/typing/list/' . $participant[ 'uesrid' ] . ':' . $participant[ 'authtoken' ], $xml );
            }
        }
        public static function Delete() { // remove person from group chat
        }
    }
?>
