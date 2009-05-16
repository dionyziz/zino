<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
        
        $mails = $mails->Get();
        if ( strpos( $mails, "@"  ) === false ){
            return;
        }
        $contactsStr = explode( ";", $mails );
        foreach ( $contactsStr as $contact ){
            $contact = explode( " ", $contact );
            $id = $contact[ 0 ];
            $mail = $contact[ 1 ];
            $contacts[ $id ] = $mail;
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
