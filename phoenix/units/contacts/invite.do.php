<?php
    function UnitContactsInvite( tText $mails ) {
        global $libs;
        global $user;
        global $rabbit_settings;
        $libs->Load( 'contacts/contacts' );
        
        $mails = $mails->Get();
        ?>alert( '<?php
        echo $mails;
        ?>' );<?php
        if ( strpos( $mails, "@"  ) ){
            return;
        }
        $contactsStr = explode( ";", $mails );
        foreach ( $contactsStr as $contact ){
            $contact = explode( " ", $contact );
            $id = $contact[ 0 ];
            $mail = $contact[ 1 ];
            $contacts[ $id ] = $mail;
            ?>alert( '<?php
            echo $id . " -> " . $mail;
            ?>');<?php
        }
        //EmailFriend( $contacts );
        ?>
        window.location = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>';<?php
    }
?>
