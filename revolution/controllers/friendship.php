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
        public static function Listing( $subdomain ) {
            global $settings;

            clude( 'models/db.php' );
            clude( 'models/friend.php' );
            clude( 'models/user.php' );
            
			
			if ( empty( $subdomain ) ) {
				return;	
			}
            $user = User::ItemByName( $subdomain );
            if ( empty( $user ) ) {                    
               return;
            }
            $userid = $user[ 'id' ]; // needed by view
            $friends = Friend::ListByUser( $userid );

            // find which of these are also friends of the active user
            if ( isset( $_SESSION[ 'user' ] ) && $_SESSION[ 'user' ][ 'name' ] != $subdomain ) {
                $friendids = array();
                foreach ( $friends as $friend ) {
                    $friendids[] = $friend[ 'id' ];
                }
                $friendsOfUser = Friend::StrengthByUserAndFriends( $_SESSION[ 'user' ][ 'id' ], $friendids );
                foreach ( $friends as $i => $friend ) {
                    if ( $friendsOfUser[ $friend[ 'id' ] ] ) {
                        $friends[ $i ][ 'friendofuser' ] = true;
                    }
                }
            }
            Template( 'friendship/list', compact( 'friends', 'friendsOfUser', 'friendids', 'subdomain' ) );
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
