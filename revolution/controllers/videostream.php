<?php
    class ControllerVideostream {
        public static function Create( $stratusid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create a videostream' );
            include_fast( 'models/db.php' );
            include_fast( 'models/videostream.php' );
            $id = $_SESSION[ 'user' ][ 'id' ];
            $success = VideoStream::Create( $id, $stratusid );

            include 'views/videostream/create.php';
        }
        public static function Listing(){
        }
        public static function View( $userid ){
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to view a videostream' );
            include_fast( 'models/db.php' );
            include_fast( 'models/videostream.php' );
            $id = $_SESSION[ 'user' ][ 'id' ];
            $response = VideoStream::Retrieve( $id, $userid );

            include 'views/videostream/view.php';
        }
        public static function Update( $recieverid ){
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to upadate a videostream' );
            include_fast( 'models/db.php' );
            include_fast( 'models/videostream.php' );
            $id = $_SESSION[ 'user' ][ 'id' ];
            $token = VideoStream::GrantPermission( $id, $recieverid );

            include 'views/videostream/update.php';
        }
        public static function Delete(){
        }
    }

?>
