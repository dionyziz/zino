<?php
    function ActionFindcontacts( tText $mail, tText $pass ) {
        global $libs;
        $libs->Load( 'contacts/contacts' );	        
        
        $mail = $mail->Get();
        $pass = $pass->Get();

        $state = GetContacts( $mail, $pass );
        if( $state == false ) {
            return Redirect( '?p=adminpanel' );                
        }
        else {       
            return Redirect( '?p=adminpanel' );    
        }
    }
