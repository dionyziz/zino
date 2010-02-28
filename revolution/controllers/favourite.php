<?php
    function Create( $typeid, $itemid ) {
        isset( $_SESSION[ 'user' ] ) or die;
        include 'models/db.php';
        include 'models/favourite.php';
        Favourite::Create( $_SESSION[ 'user' ][ 'id' ], $typeid, $itemid );
    }
?>
