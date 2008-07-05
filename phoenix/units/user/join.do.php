<?php
	function UnitUserJoin( tText $username , tText $password , tText $email ) {
		global $rabbit_settings;
		
		$username = $username->Get();
		$password = $password->Get();
		$email = $email->Get();
		$finder = New UserFinder(); 

		if ( !User_Valid( $username ) ) {
			?>alert( "Το όνομα χρήστη που επιλέξατε δεν είναι έγκυρο" );
			Join.username.focus();
			document.body.style.cursor = 'default';
			$( 'div a.button' ).removeClass( 'button_disabled' );
			Join.enabled = true;<?php
			return;
		}
		if ( strlen( $password ) < 4 ) {
			?>alert( "Ο κωδικός που επιλέξατε δεν είναι αρκετά μεγάλος" );<?php
			return;
		}
		if ( $finder->IsTaken( $username ) ) {
			?>if ( !Join.usernameexists ) {
				Join.usernameexists = true;
				$( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
			}
			<?php
		}
		else {
			$newuser = new User();
			$newuser->Name = $username;
            $newuser->Subdomain = User_DeriveSubdomain( $username );
			$newuser->Password = $password;
			if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $email )  ) {
				$newuser->Profile->Email = $email;
			}
            $_SESSION[ 's_userid' ] = $newuser->Id;
            $_SESSION[ 's_authtoken' ] = $newuser->Authtoken;
			$newuser->Save();
			User_SetCookie( $newuser->Id, $newuser->Authtoken );
			?>location.href = '<?php
			echo $rabbit_settings[ 'webaddress' ];
			?>?p=joined';<?php
		}
	}
?>
