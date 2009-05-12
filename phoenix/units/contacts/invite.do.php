<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        global $settings;
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
