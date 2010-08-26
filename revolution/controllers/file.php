<?php
    class ControllerFile {
        public static function View() {
        }
        public static function Listing() {
        }
        public static function Create() {
            if ( !isset( $_SESSION[ 'user' ] ) ) {
                throw New Exception( 'You must be logged in to do that' );
            }
            clude( 'models/db.php' );
            clude( 'models/file.php' );
            $file = File::Create( $_SESSION[ 'user' ][ 'id' ], $_FILES[ 'file' ][ 'tmp_name' ], $_FILES[ 'file' ][ 'name' ] );
            Template( 'file/create', compact( 'file' ) );
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
