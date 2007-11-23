<?php
    function ActionIPUnban( tInteger $id ) {
    	global $user;
		global $libs;
    	
		$id = $id->Get();
		
    	if ( !$user->CanModifyCategories() ) {
            return Redirect();
    	}
    	
		$libs->Load( 'ipban' );
		
		$ipban = New IPBan( $id );
		$ipban->Delete();
		
		return Redirect( "?p=userbans" );
    }
?>
