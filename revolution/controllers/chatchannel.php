<?php
    function Listing() {
    }
    function Create( $userid ) {
        include 'models/db.php';
        isset( $_SESSION[ 'userid' ] ) or die( 'You must be logged in to start a private chat' );
        include 'models/chat.php';
        $channel = array(
            'id' => Chat::Create( $_SESSION[ 'userid' ], $userid )
        );
        $participants = array(
            array( 'id' => $_SESSION[ 'userid' ], $userid )
        );
        include 'views/chatchannel/create.php';
    }
    function Update() {
    }
    function Delete() {
    }
?>


