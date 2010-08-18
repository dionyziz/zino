<?php
    class ControllerPasswordRequest {
		public static function Create( $username ) {
			clude( 'models/db.php' );
			clude( 'models/user.php' );
			clude( 'models/email.php' );
			
			$user = User::ItemByName( $username );
			if ( empty( $user ) ) {
				throw New Exception( "This username doesn't belong to a registered user" );	
			}
			$id = $user[ 'id' ];
			$details = User::ItemDetails( $id );
			if ( empty( $details[ 'profile' ][ 'email' ] ) ) {
				throw New Exception( "The user has to have set an email account" );	
			}
			$request = PasswordRequest::Create( $userid );
			//sent email

			ob_start();
			echo $settings[ 'base' ];
            $subject = PasswordRequest::Mail();
        	$message = ob_get_clean();
        	Email( $user[ 'name' ], $details[ 'profile' ][ 'email' ], $subject, $message, 'Zino', 'info@zino.gr' );
		}
	}
?>
