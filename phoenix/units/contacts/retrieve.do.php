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
            if ( $ret == "ERROR_CREDENTIALS" ){
                ?>$( "#security" ).css({
                    'background': '#ff3 url('http://static.zino.gr/phoenix/error.png') no-repeat 2px center',
                    'font-weight': 'bold',
                    'paddingLeft': '20px'
                }).html( 'Τα στοιχεία που έδωσες δεν επιβεβαιώθηκαν. Ξαναδοκίμασε με τα σωστά στοιχεία.' );<?php
            }
            ?>
            setTimeout( function(){
                contacts.backToLogin();
            }, 3000 );<?php
            return;
        }
        $contactsInZino = 0;
        $contactsNotZino = 0;
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
            else {
                ?>contacts.addContactNotZino( '<?php
                echo $mail;
                ?>' );<?php
                $contactsNotZino++;
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
        $( "#contactsNotZino > h3" ).html( "<?php
            echo $contactsNotZino;
            if ( $contactsNotZino == 1 ){
                ?> επαφή σου δεν έχει Zino ακόμα! Προσκάλεσέ την τώρα!<?php
            }
            else{
                ?> επαφές σου δεν έχουν Zino ακόμα! Προσκάλεσέ τους τώρα!<?
            }
        ?>" );
        setTimeout( function(){
                contacts.previwContactsInZino();
            }, 3000 );<?php
    }
?>
