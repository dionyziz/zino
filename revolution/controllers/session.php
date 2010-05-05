<?php
    class ControllerSession {
        public static function View() {
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $user = $_SESSION[ 'user' ];
            }
            else {
                $user = false;
            }
            include 'views/session/view.php';
        }
        public static function Create( $username = '', $password = '') {
            include_fast( 'models/db.php' );
            include_fast( 'models/user.php' );
            $data = User::Login( $username, $password );
            $success = $data !== false;
            if ( $success ) {
                $_SESSION[ 'user' ] = $data;
            }
            include 'views/session/create.php';
        }
        public static function Delete() {
            unset( $_SESSION[ 'user' ] );
            $success = true;
            include 'views/session/delete.php';
        }
    }
?>
