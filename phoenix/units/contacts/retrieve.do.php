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
        foreach( $ret as $mail ){
            if ( $members[ $mail ] != "" ){
                $theuser = new User( $members[ $mail ] );
                ?>contacts.addContactInZino( '<?php
                Element( 'user/display', $theuser->Id, $theuser->Avatar->Id, $theuser );
                ?>', '<?php
                echo $mail;
                ?>' );
                <?php
                $contactsInZino++;
            }
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
