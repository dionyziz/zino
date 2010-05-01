<?php
    class ControllerNotification {
        public static function Listing() {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to access your notifications' );

            include 'models/db.php';
            include 'models/notification.php';
            $notifications = Notification::ListRecent( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/notification/listing.php';
        }
        public static function Delete( $notificationid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a notification' );

            include 'models/notification.php';
            Notification::Delete( $notificationid, $_SESSION[ 'user' ][ 'id' ] );
        }
    }
?>
