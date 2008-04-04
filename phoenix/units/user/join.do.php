<?php
	function UnitUserJoin( tString $username , tString $password , tString $email ) {
		global $rabbit_settings;
		
		$username = $username->Get();
		$password = $password->Get();
		$email = $email->Get();
		$finder = New UserFinder(); 

		if ( User_Valid( $username ) ) {
			if ( strlen( $password ) < 4 ) {
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
				$newuser = new User();
				$newuser->Name = $username;
				$newuser->Password = $password;
				if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $email )  ) {
					$newuser->Email = $email;
				}
				$_SESSION[ 's_password' ] = $password;
				$_SESSION[ 's_username' ] = $username;
				$newuser->Save();
				User_SetCookie( $newuser->Id, $newuser->Authtoken );
				?>location.href=<?php
				echo $rabbit_settings[ 'webaddress' ];
				?>;<?php
			}
		}
		?>document.body.style.cursor = 'default';<?php
	}
?>
