<?php
    function Listing( $channelid, $userid ) {
        include 'models/db.php';
        include 'models/chat.php';
        // you can request history eitehr by channelid or indirectly by a userid
        if ( $channelid == 0 && $userid == 0 ) {
            // requesting history of public chat
        }
        else {
            isset( $_SESSION[ 'userid' ] ) or die( 'You cannot see a chat history without logging in first' );

            if ( $channelid == 0 ) {
                // the history has been requested for a user
                $channelid = Chat::Create( $_SESSION[ 'userid' ], $userid );
                // if the channel existed, it'll give us a chennlid; else it will create it and give us the channel id
                // we don't need to check for authentication in this case, as we're definately a participant 
            }
            else {
                // the history has been requested for a particular channel
                // check if the user is authorized to view this channel history
                ChatChannel::Auth( $channelid, $_SESSION[ 'userid' ] ) or die( 'You are not authorized to view this channel history' );
            }
        }
        $chatmessages = ChatMessage::ListByChannel( $channelid, 0, 100 );
        $channel = array(
            'id' => $channelid
        );
        include 'views/chatmessage/listing.php';
    }
    function Create( $channelid, $text ) {
        isset( $_SESSION[ 'userid' ] ) or die( 'You must be logged in to post a message' ); 

        include 'models/db.php';
        include 'models/chat.php';

        if ( $channelid != 0 ) {
            ChatChannel::Auth( $channelid, $_SESSION[ 'userid' ] ) or die( 'You are not authorized to post on this channel' );
        }
        $messageid = ChatMessage::Create( $channelid, $_SESSION[ 'userid' ] , $text );
        include 'views/chatmessage/create.php';
    }
    function Update() {
    }
    function Delete() {
    }
?>

