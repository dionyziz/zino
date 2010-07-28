<?php
    class ControllerNotification {
        public static function Listing() {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to access your notifications' );

            clude( 'models/db.php' );
            clude( 'models/notification.php' );
            $notifications = Notification::ListRecent( $_SESSION[ 'user' ][ 'id' ] );
            include 'views/notification/listing.php';
        }
        public static function Delete(
            $notificationid = 0,
            $itemid = 0, $eventtypeid = 0,
            $friendname = '',
            $favouriteitemid = 0, $favouritetype = 0, $favouriteuserid = 0
            ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a notification' );

            $notificationid = ( int )$notificationid;

            $itemid = ( int )$itemid;
            $eventtypeid = ( int )$eventtypeid;

            $favouriteitemid = ( int )$favouriteitemid;
            $favouriteuserid = ( int )$favouriteuserid;

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
                Notification::DeleteByInfo( EVENT_FRIENDRELATION_CREATED, $relation[ 'id' ], $_SESSION[ 'user' ][ 'id' ] );
            }
            else if ( $favouriteitemid > 0 && $favouritetype != '' && $favouriteuserid > 0 ) {
                clude( 'models/types.php' );
                switch ( $favouritetype ) {
                    case 'poll':
                        $typeid = TYPE_POLL;
                        break;
                    case 'photo':
                        $typeid = TYPE_PHOTO;
                        break;
                    case 'journal':
                        $typeid = TYPE_JOURNAL;
                        break;
                    default:
                        return;
                }
                clude( 'models/favourite.php' );
                $favourite = Favourite::ItemByDetails( $favouriteitemid, $typeid, $favouriteuserid );
                Notification::DeleteByInfo( EVENT_FAVOURITE_CREATED, $favourite[ 'id' ], $_SESSION[ 'user' ][ 'id' ] );
            }
        }
    }
?>
