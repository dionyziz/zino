<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        $libs->Load( 'contacts/contacts' );
        
        $mails = $mails->Get();
        $emails = explode( " ", $mails );
        if ( strpos( $mails, "@"  ) ){
            EmailFriend( $emails );
        }
        ?>
        window.location = <?php
        echo $settings[ 'webaddress' ];
        ?>;<?php
    }
?>
