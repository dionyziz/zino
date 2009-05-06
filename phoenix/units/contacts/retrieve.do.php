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
            ?>setTimeout( function(){
                contacts.backToLogin();
            }, 3000 );<?php
            return;
        }
        $contactsInZino = 0;
        $mailfinder = new UserProfileFinder();
        $members = $mailfinder->FindAllUsersByEmails( $ret );
        foreach( $members as $member ){
            $contact = new User( $member->profile_userid );
            ?>contacts.addContactInZino( '<?php
            echo $contact->Avatar->Id;
            ?>', '<?php
            echo $contact->Name;
            ?>', '<?php
            echo $member->profile_email;
            ?>' );
            <?php
            $contactsInZino++;
        }
        ?>$( "#contactsInZino > h3" ).html( "<?php
            echo $contactsInZino;
            if ( $contactsInZino == 1 ){
                ?> επαφή σου έχει Zino. Πρόσθεσέ την στους φίλους σου...<?php
            }
            else{
                ?> επαφές σου έχουν Zino. Πρόσθεσέ τις στους φίλους σου...<?
            }
        ?>" );
        setTimeout( function(){
                contacts.previwContactsInZino();
            }, 3000 );<?php
    }
?>
