<?php
    class ControllerBan {
		public static function Listing() {
			clude( 'models/ban.php' );
			clude( 'models/user.php' );

			$user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
			if ( ( int )$user[ 'rights' ] < 60 ) {
				throw new Exception( "Ban::Listing - Doesnt have the rights" );
			}

			$banned = Ban::Listing();
			Template( 'ban/listing', compact( 'banned' ) );
		}
	}
?>
