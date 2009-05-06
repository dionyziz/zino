<?php
    function UnitContactsRetrieve( tText $provider , tText $username, tText $password ) {
        global $libs;
        global $user;
        
        $libs->Load( 'contacts/contacts' );
        $finder = New ContactFinder();
        echo "alert(5)";
        /*
        $ret = $finder->FindByUseridAndMail( $user->Id, $username );

        if ( count( $ret ) == 0 ){
            GetContacts( $username, $password, $provider );
            $ret = $finder->FindByUseridAndMail( $user->Id, $username );
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
                echo " εοαφή σου έχει Zino. Πρόσθεσέ την στους φίλους σου...";
            }
            else{
                echo " επαφές σου έχουν Zino. Πρόσθεσέ τις στους φίλους σου...";
            }
        ?>" );<?php
    */}
?>
