<?php
    class ControllerFriendship {
        public static function View( $username ) {
            isset( $_SESSION[ 'user' ] ) or die( 'Please login' );

            clude( 'models/db.php' );
            clude( 'models/friend.php' );
            clude( 'models/user.php' );

            $b = User::ItemByName( $username );
            $strength = Friend::Strength( $_SESSION[ 'user' ][ 'id' ], $b[ 'id' ] );

            $a = User::Item( $_SESSION[ 'user' ][ 'id' ] );

            include 'views/friendship/view.php';
        }
        public static function Listing( $username ) {
            global $settings;

            clude( 'models/db.php' );
            clude( 'models/friend.php' );
            clude( 'models/user.php' );
            
            $user = User::ItemByName( $username );
            if ( empty( $user ) ) {                    
               return;
            }
            $friends = Friend::ListByUser( $user[ 'id' ] );
            include "views/friendship/list.php";
        }
        public static function Create( $friendid = 0, $username = '' ) {
            $friendid = ( int )$friendid;

            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                clude( 'models/db.php' );
                clude( 'models/friend.php' );
                clude( 'models/user.php' );
                if ( $friendid == 0 ) {
                    $friend = User::ItemByName( $username );
                    $friendid = $friend[ 'id' ];
                }
                $success = Friend::Create( $_SESSION[ 'user' ][ 'id' ], $friendid, 1 );
            }              
            include 'views/friendship/create.php';          
        }
        public static function Update() {
        }
        public static function Delete( $friendid ) {
            clude( 'models/db.php' );
            clude( 'models/friend.php' );
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $success = Friend::Delete( $_SESSION[ 'user' ][ 'id' ], $friendid );
            }              
            include 'views/friendship/delete.php';    
        }
    }
?>
