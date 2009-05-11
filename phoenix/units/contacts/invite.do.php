<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        $mails = $mails->Get();
        $emails = explode( " ", $mails );
        $libs->Load( 'contacts/contacts' );
        EmailFriend( $mails );
    }
?>
