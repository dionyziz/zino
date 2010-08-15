<?php
    class ControllerBan {
		public static function Listing() {
			clude( 'models/db.php' );
			clude( 'models/ban.php' );
			clude( 'models/user.php' );

			if ( !isset( $_SESSION[ 'user' ][ 'id' ] ) ) {
				throw new Exception( "Ban::Listing - Doesnt have the rights" );
			}

			$user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
			if ( ( int )$user[ 'rights' ] < 60 ) {
				throw new Exception( "Ban::Listing - Doesnt have the rights" );
			}

			$banned = Ban::Listing();
			Template( 'ban/listing', compact( 'banned' ) );
		}

		public static function Create( $username, $reason, $daysbanned = 20 ) {
			clude( 'models/db.php' );
			clude( 'models/ban.php' );
			clude( 'models/user.php' );

			$user = User::ItemByName( $username );
			if ( $user == false ) {
				throw new Exception( "Ban::Revoke - This user doesnt exist" );
			} 
			$userid = $user[ 'id' ];
			$oldrights = $user[ 'rights' ];
			$time_banned = ( int )$daysbanned*60*60*24;
			Ban::Create( $userid, $reason, $time_banned, $oldrights );
			User::SetRights( 0 );	
			return;
		}
		
		public static function Delete( $userid ) {
			Ban::Revoke( $userid );
		}
	}
?>
