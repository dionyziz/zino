<?php
    function ActionContactsInvite( tTextArray $approved ) {
        global $libs;
        
        $libs->Load( "contacts/contacts" );
        
        foreach ( $approved as $sample ) {
            $sample = $sample->Get();
            EmailFriend( $sample );
        }
           
        return Redirect( '?p=success');
    }
?>
