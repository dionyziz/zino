<?php
    function ActionFindcontacts( tText $email, tText $pass ) {
        global $libs;
        $libs->Load( 'contacts/contacts' );	        
        
        $email = $email->Get();
        $pass = $pass->Get();

        $state = GetContacts( $email, $pass, "googlemail" );
        if( $state === true ) {
            return Redirect( '?p=contactfinder&email=' . urlencode( $email ) . '&step=1' );    
        }
        else {       
            echo '<p>' .  $state . '</p>';
        }
    }
