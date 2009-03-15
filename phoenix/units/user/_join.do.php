<?php
    function UnitUserJoin( tText $username , tText $password , tText $email ) {
        global $rabbit_settings;
        global $libs;
		
        $username = $username->Get();
        $password = $password->Get();
        $email = $email->Get();

        die( 'hahah' );
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
        if ( !ValidEmail( $email )  ) {
            ?> $( $( 'form.joinform div > span' )[ 5 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );<?php
            return;
        }
        $finder = New UserFinder(); 
        if ( $finder->IsTaken( $username ) ) {
            ?>if ( !Join.usernameexists ) {
                Join.usernameexists = true;
                $( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
				Join.username.focus();
				Join.username.select();
				document.body.style.cursor = 'default';
            }
            <?php
        }
        else {
			$libs->Load( 'rabbddit/helpers/validate' );
            $newuser = New User();
            $newuser->Name = $username;
            $newuser->Subdomain = User_DeriveSubdomain( $username );
            $newuser->Password = $password;
            $newuser->Profile->Email = $email;
            $newuser->Save();
    		?>location.href = '<?php
			echo $rabbit_settings[ 'webaddress' ];
            ?>?p=notvalidated'<?php
        }
    }
?>
