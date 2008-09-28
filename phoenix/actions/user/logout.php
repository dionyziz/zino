<?php
	function ActionUserLogout() {
		global $user;
		global $rabbit_settings;
		
		if ( $user->Exists() ) {
			$_SESSION[ 's_username' ] = '';
			$_SESSION[ 's_password' ] = '';

			$user->RenewAuthtoken();
			$user->Save();
			User_ClearCookie();
		}

		return Redirect( $_SERVER[ 'HTTP_REFERER' ] );
	}
?>
