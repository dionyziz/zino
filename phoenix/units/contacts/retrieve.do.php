<?php
    function UnitContactsRetrieve( tText $provider , tText $username, tText $password ) {
        global $libs;
        global $user;
        $libs->Load( 'relation/relation' );
        $libs->Load( 'contacts/contacts' );
        
        $provider = $provider->Get();
        $username = $username->Get();
        $password = $password->Get();
        if ( $provider == 'gmail' || $provider == 'yahoo' ){
			$email = $username . '@' . $provider . 'com';
        }
		else{
			$email = $username;
		}
        if ( !$user->Exists() ) {
            return false;
        }
        
        $ret = GetContacts( $username, $password, $provider );
        if( !is_array( $ret ) || count( $ret ) == 0 ){
            if ( $ret == "ERROR_CONTACTS" || $ret == 'NO_CONTACTS' ){
                ?>setTimeout( function(){
                contacts.message('Δεν βρήκαμε επαφές στο <?php
                echo $provider;
                ?> σου. Προσκάλεσε κάποιον με το e-mail του.' );
				setTimeout( function(){
					contacts.changeToAddByEmail();
				}, 4000 );
                }, 3000 );<?php
                return;
            }
            ?>
                setTimeout( function(){
                contacts.changeToFindInOtherNetworks();
            <?php
                if ( $ret == 'ERROR_PROVIDER' || $ret == 'ERROR_BACKEND' ){
                    ?>$( "#security" ).css({
                        'background': '#FF9090 url(http://static.zino.gr/phoenix/xerror.png) no-repeat 6px center',
                        'font-weight': 'bold',
                        'padding': '10px 10px 10px 30px'
                    }).html( 'Υπήρξε πρόβλημα στο σύστημα. Παρακαλώ δοκίμασε αργότερα.' );
                    document.title = "Πρόβλημα στο σύστημα | Zino";<?php
                }
                if ( $ret == 'ERROR_CREDENTIALS' || $ret == 'ERROR_EMPTYCREDENTIALS' ){
                    ?>$( "#security" ).css({
                        'background': '#FEF4B7 url(http://static.zino.gr/phoenix/error.png) no-repeat 6px center',
                        'font-weight': 'bold',
                        'padding': '10px 10px 10px 30px'
                    }).html( 'Το e-mail ή ο κωδικός που έγραψες δεν είναι σωστά.' );
                    document.title = "Λάθος στοιχεία | Zino";<?php
                }
            ?>
            }, 3000 );<?php
            return;
        }
        foreach ( $ret as $name => $contact ){
            $mails[ $name ] = $contact->Mail;
        }
        $contactsInZino = 0;
        $contactsNotZino = 0;
        $mailfinder = new UserProfileFinder();
        $members = $mailfinder->FindAllUsersByEmails( $mails );
        foreach( $ret as $nickname => $contact ){
			if ( $contact->Mail == $user->Profile->Email || $contact->Mail == $email ){
				continue;
			}
            if ( $members[ $contact->Mail ] != "" ){
                $theuser = new User( $members[ $contact->Mail ] );
                $finder = New FriendRelationFinder();
				$friendship = $finder->IsFriend( $theuser, $user );
                if ( $friendship == 1 || $friendship == 3 ){
                    continue;
                }
                ?>contacts.addContactInZino( '<?php
                Element( 'user/display', $theuser->Id, $theuser->Avatar->Id, $theuser, false );
                ?>', '<?php
                echo addslashes( $contact->Mail );
                ?>', '<?php
                echo addslashes( $theuser->Profile->Location->Name );
                ?>', '<?php
                echo $theuser->Id;
                ?>' );<?php
                $contactsInZino++;
            }
            else {
                ?>contacts.addContactNotZino( '<?php
                echo addslashes( $contact->Mail );
                ?>', '<?php
                echo addslashes( $nickname );
                ?>', '<?php
                echo $contact->Id;
                ?>' );<?php
                $contactsNotZino++;
            }
        }
        if ( $contactsInZino == 0 && $contactsNotZino == 0 ){
            ?>contacts.message( 'Όλες οι επαφές σου είναι ήδη στο Zino. Προσκάλεσε κάποιον που δεν έχει Zino με το e-mail του.' );
			setTimeout( function(){
				contacts.changeToAddByEmail();
			}, 4000 );
            <?php
            return;
        }
        ?>$( "#contactsInZino > h3" ).html( "<?php
            echo $contactsInZino;
            if ( $contactsInZino == 1 ){
                ?> επαφή σου έχει Zino. Πρόσθεσέ τη στους φίλους σου...<?php
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
        $( ".contacts input" ).click( function(){
            contacts.calcCheckboxes( contacts.step );
        });
        setTimeout( function(){
                contacts.previwContacts<?php
                if ( !$contactsInZino ){
                echo "Not";
                }
                ?>InZino( <?php
                echo $contactsNotZino;
                ?> );
            }, 3000 );<?php
    }
?>
