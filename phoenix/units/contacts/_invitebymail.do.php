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
            foreach ( $emails as $email ){
				if ( $email == $user->Profile->Email || !ValidEmail( $email ) ){
					continue;
				}
                $finder = new UserProfileFinder();
                $mailid = $finder->FindAllUsersByEmails( array ( $email ) );
				if ( count( $mailid ) != 0 ){
					echo "alert( '".count( $mailid )."' )";
					foreach( $mailid as $id ){
						echo "alert( '$id' );";
					}
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
                    Element::ClearFromCache( 'user/profile/main/friends' , $user->Id );
				}
                else{
                    $contact = $contact->AddContact( $email, $user->Profile->Email );
                    $contacts[] = $contact;
                }
            }
			if ( !empty( $contacts ) ){
				EmailFriend( $contacts );
			}
        }
        ?>window.location = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>';<?php
    }
?>
