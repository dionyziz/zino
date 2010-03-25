<?php
    function Listing( $channelid ) {
        include 'models/db.php';
        include 'models/chat.php';
        $chatmessages = ChatMessage::ListByChannel( $channelid, 0, 100 );
        $chatmessages = array_reverse( $chatmessages );
        include 'views/chatmessage/listing.php';
    }
    function Create() {
    }
    function Update() {
    }
    function Delete() {
    }
?>

