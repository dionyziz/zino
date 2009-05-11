<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        $libs->Load( 'contacts/contacts' );
        
        $mails = $mails->Get();
        $emails = explode( " ", $mails );
        ?>alert('<?php
        echo sizeof( $emails );
        ?>');<?php
        if ( sizeof( $emails ) != 0 ){
            EmailFriend( $emails );
        }
        ?>
        window.location = <?php
        echo $settings[ 'webaddress' ];
        ?>;<?php
    }
?>
