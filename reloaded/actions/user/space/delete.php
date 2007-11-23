<?php
    function ActionUserSpaceDelete() {
    	global $libs;
    	global $user;
    	
    	if ( !$user->CanModifyCategories() || $user->Id() != '804' ) { // remove last condition if this file is needed
    		return Redirect( 'index.php' );
    	}
    	
    	$libs->Load( 'userspace' );
    	
    	$space = New Userspace( $user->Id() );
    	$space->Kill();
    	
        return Redirect( '?p=user&id=' . $user->Id() );
    }
?>
