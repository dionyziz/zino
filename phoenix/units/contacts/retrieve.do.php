<?php
    function UnitContactsRetrieve( tText $provider , tText $username, tText $password ) {
        global $libs;
        global $user;
        $provider = $provider->Get();
        $username = $username->Get();
        $password = $password->Get();
        
        $libs->Load( 'contacts/contacts' );
        $ret = GetContacts( $username, $password, $provider );
        
        if( !is_array( $ret ) ){
            ?>contacts.backToLogin();<?php
            return;
        }

        $contactsInZino = 1;
        foreach( $ret as $contactMail ){
            ?>$( '#contactsInZino .contacts .contact:first' ).clone()
                .children( ".contactMail" ).html("<?php
            echo $contactMail;
                ?>").end().appendTo( " #contactsInZino .contacts" );<?php
            $contactsInZino++;
        }
        ?>$( "#contactsInZino > h3" ).html( "<?php
            echo $contactsInZino;
            if ( $contactsInZino == 1 ){
                ?>εοαφή σου έχει Zino. Πρόσθεσέ την στους φίλους σου..."<?php
            }
            else{
                ?>επαφές σου έχουν Zino. Πρόσθεσέ τις στους φίλους σου..."<?
            }
        ?>" );
        contacts.previwContactsInZino();<?php
    }
?>
