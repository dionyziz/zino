<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
        
        if ( !$user->Exists() ) {
            return false;
        }
        
        if ( strlen( $mails ) != 0 ){
            $emails = explode( ';', $mails );
            $contact = new Contact();
            foreach ( $emails as $email ){
                $contact = $contact->AddContact( $email, $user->Mail );
                $contacts[] = $contact;
            }
            EmailFriend( $contacts );
        }
        ?>window.location = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>';<?php
    }
?>
