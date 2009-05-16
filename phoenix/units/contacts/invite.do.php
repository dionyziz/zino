<?php
    function UnitContactsInvite( tText $ids ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
        
        $ids = $ids->Get();
        if ( $mails != "" ){
            return;
        }
        $contact_ids = explode( ",", $mails );
        foreach ( $contact_ids as $contact_id ){
            echo "alert('" . $contact_id . "')";
            $finder = new ContactFinder();
            $contact = $finder->FindById( $contact_id );
            $contacts[] = $contact;
            echo "alert('" . $contact->Mail . "')";
        }
        EmailFriend( $contacts );
        echo "alert(2);";
        return;
        ?>
        window.location = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>';<?php
    }
?>
