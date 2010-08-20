<?php
    class ControllerBan {
		public static function Listing() {
			clude( 'models/db.php' );
			clude( 'models/ban.php' );
			clude( 'models/user.php' );

			if ( !isset( $_SESSION[ 'user' ] ) ) {
				throw new Exception( "Ban::Listing - Doesnt have the rights" );
			}

			$user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
			if ( $user[ 'rights' ] < 60 ) {
				throw new Exception( "Ban::Listing - Doesnt have the rights" );
			}

			$banned = Ban::Listing();
			Template( 'ban/listing', compact( 'banned' ) );
		}

		public static function Create( $username, $reason, $daysbanned = 20 ) {
			clude( 'models/db.php' );
			clude( 'models/ban.php' );
			clude( 'models/user.php' );

			if ( !isset( $_SESSION[ 'user' ] ) ) {
				throw new Exception( "Ban::Create - Doesnt have the rights" );
			}
            $admin = User::Item( $_SESSION[ 'user' ][ 'id' ] );
			if ( $admin[ 'rights' ] < 60 ) {
				throw new Exception( "Ban::Create - Doesnt have the rights" );
			}
			$user = User::ItemByName( $username );
			if ( $user == false ) {
				throw new Exception( "Ban::Create - This user doesnt exist" );
			} 
			$userid = $user[ 'id' ];
			$oldrights = $user[ 'rights' ];
			$time_banned = $daysbanned * 60 * 60 * 24;
			Ban::Create( $userid, $reason, $time_banned, $oldrights );
			User::SetRights( $userid, 0 );	
			return;
		}
		
		public static function Delete( $userid ) {
			clude( 'models/db.php' );
			clude( 'models/ban.php' );
			clude( 'models/user.php' );
			Ban::Revoke( $userid );
		}
	}
?>
