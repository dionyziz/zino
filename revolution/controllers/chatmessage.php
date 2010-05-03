<?php
    class ControllerChatmessage {
        public static function Listing( $channelid, $userid ) {
            include_fast( 'models/db.php' );
            include_fast( 'models/chat.php' );
            // you can request history eitehr by channelid or indirectly by a userid
            if ( $channelid == 0 && $userid == 0 ) {
                // requesting history of public chat
            }
            else {
                isset( $_SESSION[ 'user' ] ) or die( 'You cannot see a chat history without logging in first' );
    
                if ( $channelid == 0 ) {
                    // the history has been requested for a user
                    $channelid = ChatChannel::Create( $_SESSION[ 'user' ][ 'id' ], $userid );
                    // if the channel existed, it'll give us a chennlid; else it will create it and give us the channel id
                    // we don't need to check for authentication in this case, as we're definately a participant 
                }
                else {
                    // the history has been requested for a particular channel
                    // check if the user is authorized to view this channel history
                    ChatChannel::Auth( $channelid, $_SESSION[ 'user' ][ 'id' ] ) or die( 'You are not authorized to view this channel history' );
                }
            }
            $chatmessages = ChatMessage::ListByChannel( $channelid, 0, 100 );
            $channel = array(
                'id' => $channelid
            );
            include 'views/chatmessage/listing.php';
        }
        public static function Create( $channelid, $text ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to post a message' ); 
    
            include_fast( 'models/db.php' );
            include_fast( 'models/chat.php' );

            if ( $channelid != 0 ) {
                ChatChannel::Auth( $channelid, $_SESSION[ 'user' ][ 'id' ] ) or die( 'You are not authorized to post on this channel' );
            }
            $chatmessage = ChatMessage::Create( $channelid, $_SESSION[ 'user' ][ 'id' ], $text );
            $channel = array( 'id' => $channelid );
            $chatmessage[ 'name' ] = $_SESSION[ 'user' ][ 'name' ];

            ob_start();
            include 'views/chatmessage/create.php';
            $xml = ob_get_clean();

            echo $xml;
            
            // Comet
            include_fast( 'models/comet.php' );
            if ( $channelid != 0 ) {
                $participants = ChatChannel::ParticipantList( $channelid );
                foreach ( $participants as $participant ) {
                    PushChannel::Publish( 'chat/messages/list/' . $participant[ 'userid' ] . ':' . $participant[ 'authtoken' ], $xml );
                }
            }
            else {
                PushChannel::Publish( 'chat/messages/list/0', $xml );
            }
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
