<?php
    function ActionUserSpaceUpdate( tInteger $userid, tString $text ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'userspace' );

        $userid = $userid->Get();
        if ( $userid < 1 ) {
            $theuser = $user;
        }
        else {
            $theuser = New User( $userid );
        }
        $text = $text->Get();
    	
    	if ( ( $theuser->Id() != $user->Id() && !$user->CanModifyCategories() ) || $user->IsAnonymous() ) {
            return Redirect();
    	}
    	
    	$userspace = New Userspace( $theuser->Id() );
    	$update = $userspace->Update( $text );
    	
    	if ( $update ) {
            return Redirect( 'user/' . $theuser->Username() );
    	}
        die( 'Error while updating.' );
    }
?>
