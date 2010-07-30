<?php
    class ControllerChatchannel {
        public static function View( $channelid ) {
            clude( 'models/db.php' );
            isset( $_SESSION[ 'user' ][ 'id' ] ) or die( 'You must be logged in to view a private chat' );
            clude( 'models/chat.php' );

            $channelid = ( int )$channelid;

            $list = ChatChannel::ParticipantList( $channelid );

            $participants = array();
            $authorized = false;
            foreach ( $list as $participant ) {
                $userid = $participant[ 'userid' ];
                $username = $participant[ 'username' ];
                if ( $userid == $_SESSION[ 'user' ][ 'id' ] ) {
                    $authorized = true;
                }
                $participants[] = array(
                    'id' => $userid,
                    'name' => $username
                );
            }
            $authorized or die( 'Not authorized' );
            $channel = array( 'id' => $channelid );

            include 'views/chatchannel/view.php';
        }
        public static function Listing() {
        }
        public static function Create( $userid ) {
            isset( $_SESSION[ 'user' ][ 'id' ] ) or die( 'You must be logged in to start a private chat' );
            clude( 'models/db.php' );
            clude( 'models/chat.php' );
            $channel = array(
                'id' => Chat::Create( $_SESSION[ 'user' ][ 'id' ], $userid )
            );
            $participants = array(
                array( 'id' => $_SESSION[ 'user' ][ 'id' ], $userid )
            );
            include 'views/chatchannel/create.php';
        }
        public static function Update( $channelid ) { // mark all messages as read
            isset( $_SESSION[ 'user' ][ 'id' ] ) or die( 'You must be logged in to mark your chat messages as read' );
            clude( 'models/db.php' );
            clude( 'models/chat.php' );
        }
        public static function Delete() {
        }
    }
?>
