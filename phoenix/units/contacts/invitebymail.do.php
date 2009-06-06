<?php
    function UnitContactsInvitebymail( tText $mails ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
		$libs->Load( 'relation/relation' );
		$libs->Load( 'rabbit/helpers/validate' );
        
        if ( !$user->Exists() ) {
            return false;
        }
        $mails = $mails->Get();
        if ( strlen( $mails ) != 0 ){
            $emails = explode( ';', $mails );
            $contact = new Contact();
			$friends = 0;
			$invited = 0;
            foreach ( $emails as $email ){
				if ( $email == $user->Profile->Email || !ValidEmail( $email ) ){
					continue;
				}
                $finder = new UserProfileFinder();
                $mailid = $finder->FindAllUsersByEmails( array ( $email ) );
				if ( count( $mailid ) != 0 ){
                    $newUser = new User( $mailid[ $email ] );
					$friendFinder = new FriendRelationFinder();
					$friendship = $friendFinder->IsFriend( $user, $newUser );
					if ( $friendship == 1 || $friendship == 3 ){
						continue;
					}
                    $relation = New FriendRelation();
                    $relation->Userid = $user->Id;
                    $relation->Friendid = $newUser->Id;
                    $relation->Typeid = 3;
                    $relation->Save();
					++$friends;
                    Element::ClearFromCache( 'user/profile/main/friends' , $user->Id );
				}
                else{
                    $contact = $contact->AddContact( $email, $user->Profile->Email );
                    $contacts[] = $contact;
					++$invited;
                }
            }
			if ( !empty( $contacts ) ){
				EmailFriend( $contacts );
			}
        }
		if ( $friends + $invited == 0 ){
			?>window.location = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>';<?php
			return;
		}
		$message = '';
		if ( $friends ){
			$message .= "<div>Πρόσθεσες $friends φίλους.</div>";
		}
		if ( $invited ){
			$message .= "<div>Έστειλες $invited προσκλήσεις.</div>";
		}
        ?>contacts.message( "<?php 
			echo $message;
		?> ");
		setTimeout( function(){
			window.location = '<?php
			echo $rabbit_settings[ 'webaddress' ];
			?>';
		}, 4000 );<?php
    }
?>
