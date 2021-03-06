<?php
    function UnitContactsInvitebymail( tText $mails ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        
        $libs->Load( 'contacts/contacts' );
	$libs->Load( 'relation/relation' );
	$libs->Load( 'rabbit/helpers/validate' );
	$libs->Load( 'user/profile' );
	
        
        if ( !$user->Exists() ) {
            return false;
        }
        $mails = $mails->Get();
        if ( strlen( $mails ) != 0 ){
            $emails = explode( ';', $mails );
            $contact = New Contact();
			$friends = 0;
			$invited = 0;
            foreach ( $emails as $email ){
				if ( $email == $user->Profile->Email || !ValidEmail( $email ) ){
					continue;
				}
                $finder = New UserProfileFinder();
                $mailid = $finder->FindAllUsersByEmails( array ( $email ) );
				if ( count( $mailid ) != 0 ){
                    $newUser = New User( $mailid[ $email ] );
					$friendFinder = New FriendRelationFinder();
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
			?>contacts.redirectToFrontpage();<?php
			return;
		}
		$message = '';
		if ( $friends ){
			$message .= "<div>Πρόσθεσες $friends φίλ";
            if ( $friends > 1 ) {
                $message .= 'ους';
            }
            else {
                $message .= 'ο';
            }
            $message .= ".</div>";
		}
		if ( $invited ){
			$message .= "<div>Έστειλες $invited ";
            if ( $invited > 1 ) {
                $message .= 'προσκλήσεις';
            }
            else {
                $message .= 'πρόσκληση';
            }
            $message = ".</div>";
		}
        ?>contacts.message( "<?php 
			echo $message;
		?> ", contacts.redirectToFrontpage );<?php
    }
?>
