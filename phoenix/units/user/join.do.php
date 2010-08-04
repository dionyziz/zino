<?php
    function UnitUserJoin( tText $username , tText $password , tText $email ) {
        global $rabbit_settings;
        global $libs;
		global $water;
		
        $water->ExitWithoutSubmission();
        
        $libs->Load( 'contacts/contacts' );
        $libs->Load( 'relation/relation' );
		$libs->Load( 'user/profile' );
        
        $username = $username->Get();
        $password = $password->Get();
        $email = $email->Get();

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
		$mailfinder = New UserProfileFinder();
		$mailid = $mailfinder->FindAllUsersByEmails( array( $email ) );
		if ( !empty( $mailid ) ){
			?>Join.emailexists = true;
			document.body.style.cursor = 'default';
			$( $( 'form.joinform div > span' )[ 6 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );<?php
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
            $newuser = New User();
            $newuser->Name = $username;
            $newuser->Subdomain = User_DeriveSubdomain( $username );
            $newuser->Password = $password;
            $newuser->Profile->Email = $email;
            if ( isset( $_SESSION[ 'contact_id' ] ) ){
                $finder = New ContactFinder();
                $current_contact = $finder->FindById( $_SESSION[ 'contact_id' ] );
                if ( $current_contact != false ){
                    if ( $current_contact->Mail == $email ){
                        $newuser->Emailverified = 'yes';
                    }
                    $newuser->Save();
                    
                    $finder = New ContactFinder();
                    $contacts = $finder->FindByMail( $current_contact->Mail );
                    foreach ( $contacts as $contact ){
                        $relation = New FriendRelation();
                        $relation->Userid = $newuser->Id;
                        $relation->Friendid = $contact->Userid;
                        $relation->Typeid = 3;
                        $relation->Save();
                        
                        $relation = New FriendRelation();
                        $relation->Userid = $contact->Userid;
                        $relation->Friendid = $newuser->Id;
                        $relation->Typeid = 3;
                        $relation->Save();
                        Element::ClearFromCache( 'user/profile/main/friends' , $contact->Userid );
                    }
                    Element::ClearFromCache( 'user/profile/main/friends' , $newuser->Id );
                    //$current_contact->Validtoken = "";
                    //$current_contact->Save();
                    $_SESSION[ 'destuser_id' ] = $current_contact->Userid;
                    if ( $current_contact->Mail == $email ){
                        $destuser = New User( $current_contact->Userid );
                        //user login
                        $_SESSION[ 's_userid' ] = $newuser->Id;
                        $_SESSION[ 's_authtoken' ] = $newuser->Authtoken;
                        User_SetCookie( $newuser->Id, $newuser->Authtoken );
                        
                        ?>location.href = '<?php 
                        Element( 'user/url', $destuser->Id, $destuser->Subdomain );
                        ?>';<?php
                        return;
                    }
                }
            }
            $newuser->Save();
            
    		?>location.href = '<?php
			echo $rabbit_settings[ 'webaddress' ];
            ?>?p=notvalidated&firsttime=true&userid=<?php
			echo $newuser->Id;
			?>';<?php
        }
    }
?>
