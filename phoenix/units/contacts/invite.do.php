<?php
    function UnitContactsInvite( tText $ids ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
        
        $ids = $ids->Get();
        if ( $ids == "" ){
            return;
        }
        $contact_ids = explode( ",", $ids );
        foreach ( $contact_ids as $contact_id ){
            $finder = new ContactFinder();
            $contact = $finder->FindById( $contact_id );
            $contacts[] = $contact;
        }
        EmailFriend( $contacts );
        ?>
        window.location = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>';<?php
    }
?>
