<?php
	function ElementAdminSu( tString $username ) {
		global $user;
		
        // TODO: move these into the user library
        $username = $username->Get();
		if ( $username != '' && $user->IsSysOp() ) {
			$_SESSION[ 's_username' ] = $s_username = $username;
			$theuser = New User( $s_username );
			$_SESSION[ 's_password' ] = $s_password = $theuser->Password();
			
            return Redirect();
		}
        Element( '404' );
	}
	
?>