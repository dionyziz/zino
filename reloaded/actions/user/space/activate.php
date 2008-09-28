<?php
    function ActionUserSpaceActivate() {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'userspace' );
    	
    	if( !$user->Blog() ) {
    		$space = New Userspace( $user->Id() );
    		$space->Create();
    	}
    	
        return Redirect( '?p=editspace' );
    }
?>
