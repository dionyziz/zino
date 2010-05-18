<?php
    class ControllerNotification {
        public static function Listing() {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to access your notifications' );

            clude( 'models/db.php' );
            clude( 'models/notification.php' );
            $notifications = Notification::ListRecent( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/notification/listing.php';
        }
        public static function Delete( $notificationid = 0, $itemid = 0, $eventtypeid = 0, $friendname = '' ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a notification' );

            $notificationid = ( int )$notificationid;
            $itemid = ( int )$itemid;
            $eventtypeid = ( int )$eventtypeid;

            clude( 'models/db.php' );
            clude( 'models/notification.php' );

            if ( $notificationid > 0 ) {
                // delete by notificationid
                Notification::Delete( $notificationid, $_SESSION[ 'user' ][ 'id' ] );
            }
            else if ( $eventtypeid > 0 && $itemid > 0 ) { 
                // delete by ( eventtypeid, itemid ) combination
                Notification::DeleteByInfo( $eventtypeid, $itemid, $_SESSION[ 'user' ][ 'id' ] );
            }
            else if ( $friendname != '' ) {
                // delete a EVENT_FRIENDRELATION_CREATED notification by friendid
                clude( 'models/friend.php' );
                clude( 'models/user.php' );
                $friend = User::ItemByName( $friendname );
                $relation = Friend::ItemByUserIds( $friend[ 'id' ], $_SESSION[ 'user' ][ 'id' ] );
                Notification::DeleteByInfo( Event::TypeByModel( 'EVENT_FRIENDRELATION_CREATED' ), $relation[ 'id' ], $_SESSION[ 'user' ][ 'id' ] );
            }
        }
    }
?>
