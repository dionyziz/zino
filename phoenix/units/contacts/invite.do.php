<?php
    function UnitContactsInvite( tText $ids ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
        
        if ( !$user->Exists() ) {
            return false;
        }
        
        $ids = $ids->Get();
        if ( strlen( $ids ) != 0 ){
            $contact_ids = explode( ",", $ids );
            foreach ( $contact_ids as $contact_id ){
                $finder = new ContactFinder();
                $contact = $finder->FindById( $contact_id );
                $contacts[] = $contact;
            }
            EmailFriend( $contacts );
        }
        ?>contacts.redirectToFrontpage();<?php
    }
?>
