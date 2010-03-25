<?php
    function Listing() {
        include 'models/db.php';
        include 'models/chat.php';
        $users = ChatMessage::ListByChannel();
        include 'views/chatmessage/listing.php';
    }
    function Create() {
    }
    function Update() {
    }
    function Delete() {
    }
?>

