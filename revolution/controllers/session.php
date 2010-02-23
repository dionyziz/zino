<?php
    function View() {
        if ( isset( $_SESSION[ 'user' ] ) ) {
            $user = $_SESSION[ 'user' ];
        }
        else {
            $user = false;
        }
        include 'views/session/view.php';
    }
    function Create( $username, $password ) {
        include 'models/db.php';
        include 'models/user.php';
        $data = Login( $username, $password );
        $success = $data !== false;
        if ( $success ) {
            $_SESSION[ 'user' ] = $data;
        }
        include 'views/session/create.php';
    }
    function Delete() {
        unset( $_SESSION[ 'user' ] );
        include 'views/session/delete.php';
    }
?>
