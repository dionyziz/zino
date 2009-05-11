<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        $mails = $mails->Get();
        
        $libs->Load( 'contacts/contacts' );
        EmailFriend( $mails );
    }
?>
