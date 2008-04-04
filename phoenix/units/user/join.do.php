<?php
	function UnitUserJoin( tString $username , tString $password , tString $email ) {
		$username = $username->Get();
		$password = $password->Get();
		$email = $email->Get();
		$finder = New UserFinder(); 

		if ( User_Valid( $username ) ) {
			if ( !preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $username )  ) {
				?>alert( 'error' );<?php
				return;
			}
			if ( $finder->FindByName( $username ) ) {
				?>if ( !Join.usernameexists ) {
					Join.usernameexists = true;
					$( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
				}
				Join.username.focus();
				Join.username.select();
				document.body.style.cursor = 'default';<?php
			}
			else {
				?>alert( 'OK' );<?php
			}
		}
	}
?>
