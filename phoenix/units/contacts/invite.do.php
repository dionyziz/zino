<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        $libs->Load( 'contacts/contacts' );
        
        $mails = $mails->Get();
        $emails = explode( " ", $mails );
        if ( sizeof( $mails ) != 0 ){
            EmailFriend( $emails );
        }
        ?>
        window.location = <?php
        echo $settings[ 'webaddress' ];
        ?>;<?php
    }
?>
