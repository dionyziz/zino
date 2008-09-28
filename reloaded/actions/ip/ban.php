<?php
    function ActionIPBan( tString $ip ) {
    	global $user;
		global $libs;
		
		$ip = $ip->Get();
    	
    	if ( !$user->CanModifyCategories() ) {
            return Redirect();
    	}
    	
		$libs->Load( 'ipban' );
		
		$ipban = New IPBan();
		$ipban->Ip = $ip;
		$ipban->Save();
		
        return Redirect( "?p=userbans" );
    }
?>
