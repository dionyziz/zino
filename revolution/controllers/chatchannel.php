<?php
    class ControllerChatchannel {
        public static function View( $channelid ) {
            clude( 'modules/db.php' );
            isset( $_SESSION[ 'userid' ] ) or die( 'You must be logged in to start a private chat' );
            clude( 'models/chat.php' );

            $participants = ChatChannel::ParticipantList( $channelid );

            $userids = array();
            foreach ( $participants as $participant ) {
                $userid = $participant[ 'userid' ];
                if ( $userid == $_SESSION[ 'user' ][ 'id' ] ) {
                    $authorized = true;
                }
                $userids[] = $userid;
            }

            include 'views/chatchannel/view.php';
        }
        public static function Listing() {
        }
        public static function Create( $userid ) {
            clude( 'models/db.php' );
            isset( $_SESSION[ 'user' ][ 'id' ] ) or die( 'You must be logged in to start a private chat' );
            clude( 'models/chat.php' );
            $channel = array(
                'id' => Chat::Create( $_SESSION[ 'user' ][ 'id' ], $userid )
            );
            $participants = array(
                array( 'id' => $_SESSION[ 'user' ][ 'id' ], $userid )
            );
            include 'views/chatchannel/create.php';
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
