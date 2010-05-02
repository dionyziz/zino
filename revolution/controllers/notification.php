<?php
    class ControllerNotification {
        public static function Listing() {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to access your notifications' );

            include 'models/db.php';
            include 'models/notification.php';
            $notifications = Notification::ListRecent( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/notification/listing.php';
        }
        public static function Delete( $notificationid = 0, $itemid = 0, $eventtypeid = 0 ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a notification' );

            include 'models/db.php';
            include 'models/notification.php';
            if ( $notificationid > 0 ) {
                Notification::Delete( $notificationid, $_SESSION[ 'user' ][ 'id' ] );
            }
            else { 
                Notification::DeleteByInfo( $eventtypeid, $itemid, $_SESSION[ 'user' ][ 'id' ] );
            }
        }
    }
?>
