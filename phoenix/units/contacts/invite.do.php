<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        $libs->Load( 'contacts/contacts' );
        
        $mails = $mails->Get();
        $emails = explode( " ", $mails );
        if ( sizeof( $emails ) != 0 ){
            EmailFriend( $emails );
        }
        ?>
        contacts.previwContactsNotInZino();
        <?php
    }
?>
