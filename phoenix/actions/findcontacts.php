<?php
    function ActionFindcontacts( tText $email, tText $pass ) {
        global $libs;
        $libs->Load( 'contacts/contacts' );	        
        
        $email = $email->Get();
        $pass = $pass->Get();

        $state = GetContacts( $email, $pass, "hotmail" );
        if( $state == false ) {
            return Redirect( '?p=banlist' );                
        }
        else {       
            return Redirect( '?p=adminpanel' );    
        }
    }
